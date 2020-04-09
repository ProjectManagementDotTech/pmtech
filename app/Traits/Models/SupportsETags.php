<?php

namespace App\Traits\Models;

trait SupportsETags
{
    //region Public Status Report

    /**
     * Return the ETag for this model.
     *
     * @return string
     */
    public function eTag()
    {
        return md5($this->toJson());
    }

    //endregion
}
