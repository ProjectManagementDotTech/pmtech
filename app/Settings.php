<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settings extends Model
{
    use SoftDeletes;

    //region Public Access

    //endregion

    //region Public Relationships

    /**
     * The user to whom these Settings belong.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //endregion

    //region Public Status Report

    //endregion

    //region Protected Attributes

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'last_visited_view', 'user_id',
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'id', 'user_id'
    ];

    /**
     * @inheritDoc
     */
    protected $visible = [
        'last_visited_view'
    ];

    //endregion
}
