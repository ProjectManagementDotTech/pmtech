<?php

namespace App\Repositories;

use App\Project;
use App\Traits\General\GeneratesRandomColor;
use App\Workspace;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Ramsey\Uuid\Uuid;

class ProjectRepository
{
    use GeneratesRandomColor;

    //region Static Public Access

    /**
     * Archive the given project.
     * @param Project $project
     * @throws \Exception
     */
    static public function archive(Project $project)
    {
        $project->delete();
    }

    /**
     * Delete the given project.
     *
     * @param Project $project
     */
    static public function delete(Project $project)
    {
        $project->forceDelete();
    }

    /**
     * Restore, from archive, the given project.
     *
     * @param Project $project
     */
    static public function restore(Project $project)
    {
        $project->restore();
    }

    /**
     * Update the given project with the provided $data.
     *
     * @param Project $project
     * @param array $data
     */
    static public function update(Project $project, array $data)
    {
        foreach($data as $key => $value) {
            $project->$key = $value;
        }

        $project->save();
    }

    //endregion

    //region Static Public Status Report

    /**
     * Retrieve all projects from the repository.
     *
     * @param bool $includeArchivedProjects
     * @return Collection
     */
    static public function all(bool $includeArchivedProjects = FALSE):
        EloquentCollection
    {
        if($includeArchivedProjects) {
            return Project::withTrashed()->get();
        } else {
            return Project::all();
        }
    }

    /**
     * Get the project identified by the given $name in the given $workspace.
     *
     * @param string $name
     * @param Workspace $workspace
     * @return Project|null
     */
    static public function byName(string $name, Workspace $workspace): ?Project
    {
        return Project::query()
            ->where('workspace_id', $workspace->id)
            ->where('name', $name)
            ->first();
    }

    /**
     * Create a new project based on the given data.
     *
     * @param array $data
     * @return Project
     * @throws \Exception
     */
    static public function create(array $data): Project
    {
        $data['id'] = Uuid::uuid4()->toString();
        if(!isset($data['color'])) {
            $data['color'] = self::generateRandomColor();
        }
        $project = Project::create($data);

        return $project;
    }

    /**
     * The project matching the given id.
     *
     * @param string $id
     * @return Project|null
     */
    static public function find(string $id): ?Project
    {
        return Project::find($id);
    }

    //endregion
}
