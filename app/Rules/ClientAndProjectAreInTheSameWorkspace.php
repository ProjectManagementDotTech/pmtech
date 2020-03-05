<?php

namespace App\Rules;

use App\Client;
use App\Repositories\ClientRepository;
use Illuminate\Contracts\Validation\Rule;

class ClientAndProjectAreInTheSameWorkspace implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $client = Client::find($value);
        if(request()->method() == 'POST') {
            $workspace = request()->route('workspace');
            return $workspace->id == $client->workspace_id;
        } elseif(request()->method() == 'PUT') {
            $project = request()->route('project');
            return $project->workspace_id === $client->workspace_id;
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
        return 'The client and the project must exist in the same workspace.';
    }
}
