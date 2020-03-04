<?php

namespace App\Http\Requests\v1;

use App\Repositories\Contracts\ClientRepository;
use App\Rules\UniqueClientNameWithinWorkspace;
use Illuminate\Foundation\Http\FormRequest;

class CreateClientRequest extends FormRequest
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
    public function rules(ClientRepository $clientRepository)
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                /* BR000015 */
                new UniqueClientNameWithinWorkspace($clientRepository)
            ]
        ];
    }
}
