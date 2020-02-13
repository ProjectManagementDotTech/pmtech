<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    //region Public Access
    //endregion

    //region Public Relationships

    /**
     * The project to which this task belongs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * The timesheet entries that belong to this task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timesheetEntries()
    {
        return $this->hasMany(TimesheetEntry::class);
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
        'id', 'name', 'project_id', 'wbs',
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'project_id',
    ];

    /**
     * @inheritDoc
     */
    protected $keyType = 'string';

    /**
     * @inheritDoc
     */
    protected $visible = [
        'id', 'name', 'wbs',
    ];

    //endregion
}
