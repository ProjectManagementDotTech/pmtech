<?php

namespace App\Repositories\Concerns;

use App\Exceptions\RestoreFailedException;
use Illuminate\Database\Eloquent\Model;

trait DeletesModel
{
    //region Public Access

    /**
     * @inheritDoc
     */
    public function archive(Model $model)
    {
        if($this->usesSoftDeletes) {
            $model->delete();
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(Model $model)
    {
        if($this->usesSoftDeletes) {
            $model->forceDelete();
        } else {
            $model->delete();
        }
    }

    /**
     * @inheritDoc
     */
    public function restore(Model $model)
    {
        if($this->usesSoftDeletes) {
            if(!$model->restore()) {
                throw new RestoreFailedException($model);
            }
        }
    }

    //endregion
}
