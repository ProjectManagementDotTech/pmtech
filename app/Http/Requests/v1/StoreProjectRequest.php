<?php

namespace App\Http\Requests\v1;

use App\Rules\ClientAndProjectAreInTheSameWorkspace;
use App\Rules\UniqueProjectNameWithinWorkspace;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
        if(request()->method() == 'POST') {
            return [
                'client_id' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'exists:clients,id',
                    new ClientAndProjectAreInTheSameWorkspace
                ],
                'color' => 'sometimes|nullable|string',
                'name' => [
                    'required',
                    'string',
                    new UniqueProjectNameWithinWorkspace
                ],
            ];
        } elseif(request()->method() == 'PUT') {
            return [
                'client_id' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'exists:clients,id',
                    new ClientAndProjectAreInTheSameWorkspace
                ],
                'color' => 'sometimes|nullable|string',
                'name' => [
                    'sometimes',
                    'string',
                    new UniqueProjectNameWithinWorkspace
                ],
            ];
        } else {
            return [];
        }
    }
}
