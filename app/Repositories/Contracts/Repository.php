<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Repository
{
    /**
     * Create a new model based on $attributes.
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes = []): Model;

    /**
     * Find the model with primary key $id.
     *
     * @param $id
     * @return Model|null
     */
    public function find($id): ?Model;
}
