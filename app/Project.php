<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    //region Public Access
    //endregion

    //region Public Relationships

    /**
     * The tasks that belong to this project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * The timesheet entries that belong to this project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timesheetEntries()
    {
        return $this->hasMany(TimesheetEntry::class);
    }

    /**
     * The users that belong to this project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * The workspace to which this project belongs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    //endregion

    //region Public Status Reports

    /**
     * @inheritDoc
     */
    public $incrementing = FALSE;

    //endregion

    //region Protected Attributes

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'color', 'id', 'name', 'workspace_id'
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'workspace_id',
    ];

    /**
     * @inheritDoc
     */
    protected $keyType = 'string';

    /**
     * @inheritDoc
     */
    protected $visible = [
        'color', 'id', 'name',
    ];

    //endregion
}
