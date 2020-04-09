<?php

namespace App\Repositories\Concerns;

use Illuminate\Database\Eloquent\Model;

trait FindsModel
{
    //region Public Status Report

    /**
     * @inheritDoc
     */
    public function find($id): ?Model
    {
        return $this->modelClass::find($id);
    }

    //endregion
}
