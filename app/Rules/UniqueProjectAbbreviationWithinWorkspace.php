<?php

namespace App\Rules;

use App\Repositories\ProjectRepository;
use Illuminate\Contracts\Validation\Rule;

class UniqueProjectAbbreviationWithinWorkspace implements Rule
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
        $project = NULL;
        $workspace = request()->route('workspace');
        if(!$workspace) {
            $project = request()->route('project');
            $workspace = $project->workspace;
        }
        $existingProject = ProjectRepository::byAbbreviation($value,
            $workspace);

        if($project) {
            return $existingProject != NULL && $existingProject->id == $project->id;
        }

        return $existingProject == NULL;
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
