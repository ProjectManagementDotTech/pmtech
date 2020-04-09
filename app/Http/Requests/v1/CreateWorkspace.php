<?php

namespace App\Http\Requests\v1;

use App\Repositories\Contracts\WorkspaceRepositoryInterface;
use App\Rules\UniqueWorkspaceNameForOwnerUser;
use Illuminate\Foundation\Http\FormRequest;

class CreateWorkspace extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(WorkspaceRepositoryInterface $workspaceRepository)
    {
        return [
            'name' => [
                'required',
                'string',
                new UniqueWorkspaceNameForOwnerUser($workspaceRepository)
            ]
        ];
    }
}
