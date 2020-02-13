<?php

namespace App\Rules;

use App\Repositories\WorkspaceRepository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UniqueWorkspaceNameForOwnerUser implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = Auth::user();
        if($user) {
            $existingWorkspaces = WorkspaceRepository::filter([
                'owner_user_id' => $user->id,
                'name' => $value
            ]);
            return count($existingWorkspaces) == 0;
        }

        return FALSE;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is already used.';
    }
}
