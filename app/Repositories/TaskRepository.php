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
    use DeletesModel, FindsModel;
    use UpdatesModel {
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

    //region Static Public Access

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
         * We cannot move tasks between projects and we cannot set a new ID.
         */
        unset($attributes['id']);
        unset($attributes['project_id']);

        $this->traitUpdate($model, $attributes);

//        event(new TaskUpdated($model));
    }

    //endregion

    //region Static Public Status Report

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
    public function findByName(string $name, $projectOrWorkspace): Collection
    {
        $builder = Task::query();

        if($projectOrWorkspace instanceof Project) {
            $builder->where('project_id', $projectOrWorkspace->id);
        } else {
            $builder->where('project.workspace_id', $projectOrWorkspace->id);
        }

        return $builder->get();
    }

    //endregion
}
