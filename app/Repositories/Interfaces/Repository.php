<?php

namespace App\Repositories\Interfaces;

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
}
