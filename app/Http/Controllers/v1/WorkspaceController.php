<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CreateProject;
use App\Http\Requests\v1\CreateWorkspace;
use App\Repositories\ProjectRepository;
use App\Repositories\WorkspaceRepository;
use App\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    //region Public Status Report

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
     * Create a new project, based on the given input, in the given workspace.
     *
     * @param Request $request
     * @param Workspace $workspace
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function createProject(CreateProject $request, Workspace $workspace)
    {
        $data = [
            'color' => $request->color,
            'name' => $request->name,
            'workspace_id' => $workspace->id,
        ];
        $project = ProjectRepository::create($data);
        $project->users()->attach(Auth::user()->id);

        return response('', 201, [
            'Location' => route('projects.show', ['project' => $project->id])
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
     * Update the given $workspace with information from the $request.
     *
     * @param Request $request
     * @param Workspace $workspace
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
    }

    //endregion
}
