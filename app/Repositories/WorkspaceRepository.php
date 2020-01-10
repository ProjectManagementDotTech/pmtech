<?php

namespace App\Repositories;

use App\Events\WorkspaceUpdated;
use App\Workspace;
use Ramsey\Uuid\Uuid;

class WorkspaceRepository
{
    //region Static Public Access

    /**
     * Archive the given Workspace.
     *
     * @param Workspace $workspace
     * @throws \Exception
     */
    static public function archive(Workspace $workspace)
    {
        $workspace->delete();
    }

    /**
     * Delete the given Workspace.
     *
     * @param Workspace $workspace
     */
    static public function delete(Workspace $workspace)
    {
        $workspace->forceDelete();
    }

    /**
     * Restore, from archive, the given workspace.
     *
     * @param Workspace $workspace
     */
    static public function restore(Workspace $workspace)
    {
        $workspace->restore();
    }

    /**
     * Update the given workspace with the provided $data.
     *
     * @param Workspace $workspace
     * @param array $data
     */
    static public function update(Workspace $workspace, array $data)
    {
        foreach($data as $key => $value) {
            $workspace->$key = $value;
        }

        $workspace->save();

        event(new WorkspaceUpdated($workspace));
    }

    //endregion

    //region Static Public Status Report

    /**
     * All the workspaces owned by the same owner as the given $workspace,
     * except for the given $workspace.
     *
     * @param Workspace $workspace
     * @return mixed
     */
    static public function allFromSameOwnerExcept(Workspace $workspace)
    {
        return $workspace->ownerUser->ownedWorkspaces()
            ->whereNot('id', $workspace->id)
            ->get();
    }

    /**
     * Create a new workspace based on the given data.
     *
     * @param array $data
     * @return Workspace
     * @throws \Exception
     */
    static public function create(array $data): Workspace
    {
        $data['id'] = Uuid::uuid4()->toString();
        return Workspace::create($data);
    }

    /**
     * Get the workspace identified by $id.
     *
     * @param string $id
     * @return Workspace
     */
    static public function get(string $id): Workspace
    {
        return Workspace::find($id);
    }

    /**
     * Restore, from archive, a Workspace model given its ID.
     *
     * @param string $id
     * @return Workspace|null
     */
    static public function restoreById(string $id): ?Workspace
    {
        $workspace = Workspace::withTrashed()->where('id', $id)->first();
        if($workspace)
            $workspace->restore();

        return $workspace;
    }

    //endregion
}
