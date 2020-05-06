<?php

namespace App\Rules;

use App\Repositories\Contracts\ClientRepositoryInterface;
use Illuminate\Contracts\Validation\Rule;

class UniqueClientNameWithinWorkspace implements Rule
{
    //region Construction

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    //endregion

    //region Status Report

    /**
     * Determine if the validation rule passes.
     * This implements BR000015
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $existingClient = $this->clientRepository->findFirstByNameInsideWorkspace(
            $value, request()->route('workspace'), TRUE
        );
        return $existingClient == NULL;
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

    //endregion

    //region Protected Attributes

    /**
     * @var ClientRepositoryInterface
     */
    protected $clientRepository;

    //endregion
}
