<?php

namespace App\Repositories;

use App\Project;
use App\Repositories\Concerns\ConstructsRepository;
use App\Repositories\Concerns\CreatesModel;
use App\Repositories\Concerns\DeletesModel;
use App\Repositories\Concerns\FindsModel;
use App\Repositories\Concerns\UpdatesModel;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TaskRepository implements TaskRepositoryInterface
{
    use ConstructsRepository, CreatesModel {
        create as traitCreate;
    }
    use DeletesModel, FindsModel, UpdatesModel {
        update as traitUpdate;
    }

    //region Public Construction

    /**
     * TaskRepository constructor.
     */
    public function __construct()
    {
        $this->modelClass = Task::class;
        $this->usesSoftDeletes = TRUE;
    }

    //endregion

    //region Public Access

    /*
     * TODO Make sure there are no un-archived timesheet entries against
     *   the task
     */

    /**
     * @inheritDoc
     */
    public function update(Model $model, array $attributes = [])
    {
        /*
         * We cannot move tasks between projects.
         */
        unset($attributes['project_id']);

        $this->traitUpdate($model, $attributes);
    }

    //endregion

    //region Public Status Report

    /**
     * @inheritDoc
     */
    public function create(array $attributes = []): Model
    {
        /*
         * TODO Use ProjectRepository
         */
        $project = Project::find($attributes['project_id']);
        $attributes['wbs'] = $project->tasks()->count() + 1;

        $task = $this->traitCreate($attributes);

//        event(new ProjectUpdated($data['project_id']));

        return $task;
    }

    /**
     * @inheritDoc
     */
    public function findAllByName(string $name, $projectOrWorkspace,
        bool $paginate = TRUE)
    {
        $builder = Task::query();

        if($projectOrWorkspace instanceof Project) {
            $builder->where('project_id', $projectOrWorkspace->id);
        } else {
            $builder->where('project.workspace_id', $projectOrWorkspace->id);
        }

        if($paginate) {
            return $builder->paginate();
        } else {
            return $builder->get();
        }
    }

    /**
     * @inheritDoc
     */
    public function findFirstByName(string $name, $projectOrWorkspace): ?Task
    {
        $builder = Task::query();

        if($projectOrWorkspace instanceof Project) {
            $builder->where('project_id', $projectOrWorkspace->id);
        } else {
            $builder->where('project.workspace_id', $projectOrWorkspace->id);
        }

        return $builder->first();
    }

    //endregion
}
