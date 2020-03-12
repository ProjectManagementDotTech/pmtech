<?php

namespace App;

use IgnitionNbs\LaravelUuidModel\UuidModel;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use UuidModel;

    //region Protected Attributes

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'height', 'user_agent', 'width'
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'created_at', 'height', 'id', 'updated_at', 'user_agent', 'width'
    ];

    /**
     * @inheritDoc
     */
    protected $visible = [

    ];

    //endregion
}
