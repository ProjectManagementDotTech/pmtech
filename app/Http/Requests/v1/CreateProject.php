<?php

namespace App\Http\Requests\v1;

use App\Rules\UniqueProjectNameWithinWorkspace;
use Illuminate\Foundation\Http\FormRequest;

class CreateProject extends FormRequest
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
                new UniqueProjectNameWithinWorkspace
            ]
        ];
    }
}
