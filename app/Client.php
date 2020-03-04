<?php

namespace App;

use IgnitionNbs\LaravelUuidModel\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes, UuidModel;

    //region Public Relationships

    /**
     * The workspace that this client belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    //endregion

    //region Protected Attributes

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'created_at', 'deleted_at', 'updated_at', 'workspace_id'
    ];

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'name', 'workspace_id'
    ];

    /**
     * @inheritDoc
     */
    protected $visible = [
        'id', 'name'
    ];

    //endregion
}
