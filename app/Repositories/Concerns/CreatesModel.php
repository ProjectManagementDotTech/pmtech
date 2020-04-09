<?php

namespace App\Repositories\Concerns;

use Illuminate\Database\Eloquent\Model;

trait CreatesModel
{
    /**
     * @inheritDoc
     */
    public function create(array $attributes = []): Model
    {
        return $this->modelClass::create($attributes);
    }
}
