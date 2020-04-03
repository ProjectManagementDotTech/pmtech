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
        unset($attributes['id']);

        $model->update($attributes);
        $model->save();
    }

    //endregion
}
