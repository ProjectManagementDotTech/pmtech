<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    //region Public Relationships

    /**
     * The user who sent the invitation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The workspace in which the invitation was created.
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
    protected $fillable = [
        'email', 'nonce', 'user_id', 'workspace_id'
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'created_at', 'email', 'id', 'updated_at', 'user_id', 'workspace_id'
    ];

    //endregion
}
