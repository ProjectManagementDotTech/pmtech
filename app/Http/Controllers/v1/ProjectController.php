<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StoreProjectRequest;
use App\Project;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    //region Public Construction

    /**
     * ProjectController constructor.
     *
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    //endregion

    //region Public Status Report

    public function createTask(Request $request, Project $project)
    {
        $data = [
            'project_id' => $project->id,
            'name' => $request->name
        ];
        $task = $this->taskRepository->create($data);

        return response('', 201, [
            'Location' => route('tasks.show', ['task' => $task->id])
        ]);
    }

    /**
     * List all the tasks in the given project.
     *
     * @param Request $request
     * @param Project $project
     * @return mixed
     */
    public function indexTasks(Request $request, Project $project)
    {
        $sort = $request->get('sort', NULL);
        if($sort == NULL) {
            $sortColumn = 'wbs';
            $sortDirection = 'asc';
        } else {
            $sortParts = explode('|', $sort);
            $sortColumn = $sortParts[0];
            $sortDirection = $sortParts[1];
        }

        $tasks = $project
            ->tasks()
            ->orderBy($sortColumn, $sortDirection);
        if($request->per_page && $request->page) {
            return $tasks->paginate($request->per_page);
        } else {
            return $tasks->get();
        }
    }

    /**
     * Update $project according to $request.
     *
     * @param StoreProjectRequest $request
     * @param Project $project
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(StoreProjectRequest $request, Project $project)
    {
        ProjectRepository::update($project, $request->validated());

        return response('', 204);
    }

    //endregion

    //region Protected Attributes

    /**
     * The task repository.
     *
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    //endregion
}
