<?php

namespace App;

use IgnitionNbs\LaravelUuidModel\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes, UuidModel;

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

    //endregion

    //region Protected Attributes

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'id', 'name', 'project_id', 'wbs', 'work_driven'
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'created_at', 'deleted_at', 'project_id', 'updated_at'
    ];

    /**
     * @inheritDoc
     */
    protected $visible = [
        'id', 'name', 'wbs', 'work_driven'
    ];

    //endregion
}
