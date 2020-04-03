<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;

class RestoreFailedException extends Exception
{
    //region Public Construction

    /**
     * RestoreFailedException constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        parent::__construct('Failed to restore ' . get_class($model) . '#' .
            $model->id);
    }

    //endregion
}
