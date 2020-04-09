<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    //region Public Access

    /**
     * Archive $model, if it supports SoftDeletes
     *
     * @param Model $model
     * @return void
     */
    public function archive(Model $model);

    /**
     * Delete $model from the database.
     *
     * @param Model $model
     * @return void
     */
    public function delete(Model $model);

    /**
     * Restore $model, if it supports SoftDeletes.
     *
     * @param Model $model
     * @return void
     */
    public function restore(Model $model);

    /**
     * Update $model according to $attributes, and save it.
     *
     * @param Model $model
     * @param array $attributes
     * @return void
     */
    public function update(Model $model, array $attributes = []);

    //endregion

    //region Public Status Report

    /**
     * Create a new model based on $attributes.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes = []): Model;

    /**
     * Find the model with primary key $id.
     *
     * @param $id
     * @return Model|null
     */
    public function find($id): ?Model;

    //endregion
}
