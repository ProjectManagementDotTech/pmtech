<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    //region Public Access

    /**
     * Store `start_date` which is expected to have the format `d/m/Y`.
     *
     * @param $value
     */
    public function setStartDateAttribute($value)
    {
        $valueDate = Carbon::createFromFormat('d/m/Y', $value);
        $this->attributes['start_date'] = $valueDate->format('Y-m-d');
    }

    //endregion

    //region Public Relationships

    /**
     * The client to which this project belongs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

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

    /**
     * Get the `start_date` attribute.
     *
     * @param $value
     * @return Carbon
     */
    public function getStartDateAttribute($value)
    {
        if($value) {
            return Carbon::createFromFormat('Y-m-d', $value);
        } else {
            return $value;
        }
    }

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
        'abbreviation', 'client_id', 'color', 'id', 'name', 'start_date',
        'workspace_id'
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'created_at', 'deleted_at', 'workspace_id', 'updated_at'
    ];

    /**
     * @inheritDoc
     */
    protected $keyType = 'string';

    /**
     * @inheritDoc
     */
    protected $visible = [
        'abbreviation', 'client_id', 'color', 'id', 'name', 'start_date'
    ];

    //endregion
}
