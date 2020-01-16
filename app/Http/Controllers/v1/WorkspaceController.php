<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Repositories\ProjectRepository;
use App\Repositories\WorkspaceRepository;
use App\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    //region Public Status Report

    /**
     * Create a new project, based on the given input, in the given workspace.
     *
     * @param Request $request
     * @param Workspace $workspace
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function createProject(Request $request, Workspace $workspace)
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
