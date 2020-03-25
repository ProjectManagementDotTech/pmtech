<?php

namespace App\Repositories\Concerns;

use Illuminate\Database\Eloquent\Model;

trait UpdatesModel
{
    //region Public Access

    /**
     * @inheritDoc
     */
    public function update(Model $model, array $attributes = [])
    {
        $model->update($attributes);
        $model->save();
    }

    //endregion
}
