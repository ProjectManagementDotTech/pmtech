<?php

namespace App\Repositories\Concerns;

trait ConstructsRepository
{
    //region Public Access

    /**
     * Initialize the repository.
     */
    public function bootConstructsRepository()
    {

    }

    //endregion

    //region Status Report

    /**
     * The classname of the model represented by this repository.
     *
     * @return string
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }

    //endregion

    //region Protected Attributes

    /**
     * The classname of the model represented by this repository.
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Does the model represented by this repository use SoftDeletes?
     *
     * @var bool
     */
    protected $usesSoftDeletes;

    //endregion
}
