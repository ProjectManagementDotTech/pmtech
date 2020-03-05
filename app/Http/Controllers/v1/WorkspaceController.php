<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CreateClientRequest;
use App\Http\Requests\v1\StoreProjectRequest;
use App\Http\Requests\v1\CreateWorkspace;
use App\Http\Requests\v1\InvitationRequest;
use App\Mail\Invitation;
use App\Repositories\Contracts\ClientRepository;
use App\Repositories\Contracts\InvitationRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use App\User;
use App\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;

class WorkspaceController extends Controller
{
    //region Public Construction

    /**
     * WorkspaceController constructor.
     *
     * @param InvitationRepository $invitationRepository
     */
    public function __construct(ClientRepository $clientRepository,
        InvitationRepository $invitationRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->invitationRepository = $invitationRepository;
    }

    //endregion

    //region Public Status Report

    /**
     * Archive $workspace and send a header location back as to which workspace
     * should be loaded in the front-end.
     *
     * @param Workspace $workspace
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function archive(Workspace $workspace)
    {
        WorkspaceRepository::archive($workspace);

        return response('', 205, [
            'Location' => route('workspaces.show', [
                'workspace' => Auth::user()->workspaces[0]->id
            ])
        ]);
    }

    /**
     * The balance currently on the workspace subscription.
     *
     * @param Workspace $workspace
     * @return float|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|int
     */
    public function balance(Workspace $workspace)
    {
        $user = Auth::user();
        if($subscription = $user->subscription($workspace->id)) {
            if($subscription->stripe_status != 'active') {
                return [
                    'balance' => $workspace->subscriptionFee(),
                ];
            } else {
                return 0;
            }
        } else {
            return [
                'initial_charge' => $workspace->subscriptionFee()
            ];
        }
    }

    /**
     * Create a new workspace.
     *
     * @param CreateWorkspace $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function create(CreateWorkspace $request)
    {
        $data = $request->input();
        $data['owner_user_id'] = Auth::user()->id;
        $workspace = WorkspaceRepository::create($data);

        if($workspace) {
            return response('', 201, [
                'Location' => route('workspaces.show', [
                    'workspace' => $workspace->id
                ])
            ]);
        } else {
            abort(500);
        }
    }

    /**
     * Create a new client in $workspace.
     *
     * @param CreateClient $request
     * @param Workspace $workspace
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function createClient(CreateClientRequest $request, Workspace $workspace)
    {
        $data = $request->validated();
        $data['workspace_id'] = $workspace->id;

        $client = $this->clientRepository->create($data);

        return response('', 201, [
            'Location' => route('clients.show', ['client' => $client->id])
        ]);
    }

    /**
     * Create a new project in $workspace.
     *
     * @param Request $request
     * @param Workspace $workspace
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function createProject(StoreProjectRequest $request, Workspace $workspace)
    {
        $data = $request->validated();
        $data['workspace_id'] = $workspace->id;

        $project = ProjectRepository::create($data);
        $project->users()->attach(Auth::user()->id);

        return response('', 201, [
            'Location' => route('projects.show', ['project' => $project->id])
        ]);
    }

    /**
     * Delete $workspace and send a header location back as to which workspace
     * should be loaded in the front-end.
     *
     * @param Workspace $workspace
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete(Workspace $workspace)
    {
        WorkspaceRepository::delete($workspace);

        return response('', 205, [
            'Location' => route('workspaces.show', [
                'workspace' => Auth::user()->workspaces[0]->id
            ])
        ]);
    }

    /**
     * All the workspaces that the user owns.
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return $request->user()->workspaces;
    }

    /**
     * List all members of $workspace.
     *
     * @param Workspace $workspace
     * @return mixed
     */
    public function indexMembers(Workspace $workspace)
    {
        $invitations = $this->invitationRepository->byWorkspace($workspace);
        $result = [];
        foreach($invitations as $invitation) {
            $result[] = [
                'name' => $invitation->email . ' (invitation pending ' .
                    'acceptance)'
            ];
        }

        foreach($workspace->users as $user) {
            $result[] = $user;
        }

        return $result;
    }

    /**
     * Return paginated list of clients that belong to $workspace.
     *
     * @param Workspace $workspace
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function indexClients(Request $request, Workspace $workspace)
    {
        if($request->input('per_page', NULL) == NULL) {
            return $workspace->clients()->orderBy('name', 'asc')->get();
        } else {
            return $workspace->clients()->orderBy('name', 'asc')->paginate();
        }

    }

    /**
     * List all the projects in the given workspace.
     *
     * @param Request $request
     * @param Workspace $workspace
     * @return mixed
     */
    public function indexProjects(Request $request, Workspace $workspace)
    {
        /*
         * TODO Allow orderBy
         */

        $result = Auth::user()
            ->projects()
            ->where('workspace_id', $workspace->id);

        if($request->per_page && $request->page) {
            return $result->paginate($request->per_page);
        } else {
            return $result->get();
        }
    }

    /**
     * Invite the email address (per $request) to $workspace.
     *
     * @param Request $request
     * @param Workspace $workspace
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function invite(InvitationRequest $request, Workspace $workspace)
    {
        $user = UserRepository::byEmail($request->email);
        if(!$user) {
            /*
             * This is a completely new user, and thus, we have to go through
             * the process of sending the new user an invitation email, etc...
             */
            Cache::store('database')
                ->put($request->email, Uuid::uuid4()->toString(), 3600);
            $invitation = $this->invitationRepository->create([
                'user_id' => Auth::user()->id,
                'workspace_id' => $workspace->id,
                'email' => $request->email,
                'nonce' => Uuid::uuid4()->toString()
            ]);

            Mail::to($request->email)->send(new Invitation($invitation));

            return response('', 201);
        } else {
            $user->attachToWorkspace($workspace);
            return response('', 201);
        }
    }

    /**
     * Show the workspace.
     *
     * @param Workspace $workspace
     * @return Workspace
     */
    public function show(Workspace $workspace)
    {
        return $workspace;
    }

    /**
     * Transfer ownership of $workspace to $newOwner
     *
     * @param Workspace $workspace
     * @param User $newOwner
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function transferOwnership(Workspace $workspace, User $newOwner)
    {
        $workspace->owner_user_id = $newOwner->id;
        $workspace->save();

        return response('', 201);
    }

    /**
     * Update the given $workspace with information from the $request.
     *
     * @param Request $request
     * @param Workspace $workspace
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, Workspace $workspace)
    {
        $validatedData = $request->validate([
            'name' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($workspace) {
                    foreach(
                        WorkspaceRepository::allFromSameOwnerExcept($workspace)
                        as $ownedWorkspace
                    ) {
                        if($ownedWorkspace->name == $value) {
                            $fail('The given value is already in use for ' .
                                $attribute . '.');
                        }
                    }
                }
            ]
        ]);

        WorkspaceRepository::update($workspace, $validatedData);

        return response('', 204);
    }

    //endregion

    //region Protected Attributes

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var InvitationRepository
     */
    protected $invitationRepository;

    //endregion
}
