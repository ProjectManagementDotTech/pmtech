<?php

namespace App\Http\Requests\v1;

use App\Rules\UniqueWorkspaceNameForOwnerUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                new UniqueWorkspaceNameForOwnerUser
            ]
        ];
    }
}
