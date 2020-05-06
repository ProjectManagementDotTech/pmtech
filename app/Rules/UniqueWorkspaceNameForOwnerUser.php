<?php

namespace App\Rules;

use App\Repositories\Contracts\WorkspaceRepositoryInterface;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UniqueWorkspaceNameForOwnerUser implements Rule
{
    //region Public Construction

    /**
     * UniqueWorkspaceNameForOwnerUser constructor.
     *
     * @param WorkspaceRepositoryInterface $workspaceRepository
     */
    public function __construct(
        WorkspaceRepositoryInterface $workspaceRepository)
    {
        $this->workspaceRepository = $workspaceRepository;
    }

    //endregion

    //region Public Status Report

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is already used.';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $workspace = request()->route('workspace');
        if($workspace) {
            $otherOwnedWorkspaces = $this->workspaceRepository
                ->findAllOtherWorkspacesFromSameOwner($workspace);
            foreach($otherOwnedWorkspaces as $ownedWorkspace) {
                if($ownedWorkspace->name == $value) {
                    return FALSE;
                }
            }

            return TRUE;
        }

        $user = Auth::user();
        if($user) {
            $existingWorkspaces = $this->workspaceRepository
                ->findAllByNameAndUser($value, $user);
            return count($existingWorkspaces) == 0;
        }

        return FALSE;
    }

    //endregion

    //region Protected Attributes

    /**
     * The workspace repository.
     *
     * @var WorkspaceRepositoryInterface
     */
    protected $workspaceRepository;

    //endregion
}
