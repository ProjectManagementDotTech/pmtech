<?php

namespace App;

use App\Traits\Time\ConvertsSecondsToHumanReadableDuration;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimesheetEntry extends Model
{
    use ConvertsSecondsToHumanReadableDuration, SoftDeletes;

    //region Public Access
    //endregion

    //region Public Relationships

    /**
     * The project to which this timesheet entry belongs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * The task to which this timesheet entry belongs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * The user to which this timesheet entry belongs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The workspace to which this timesheet entry belongs.
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
     * Get the human readable `duration` attribute for this timesheet entry.
     *
     * @return string
     */
    public function getDurationAttribute()
    {
        return $this->convertSecondsToHumanReadableDuration(
            $this->total_seconds);
    }

    /**
     * Get the `total_seconds` attribute for this timesheet entry.
     *
     * @return int
     */
    public function getTotalSecondsAttribute()
    {
        if($this->started_at) {
            $endedAt = $this->ended_at;
            if(!$endedAt) {
                $endedAt = Carbon::now();
            }
            return $this->started_at->diffInSeconds($endedAt);
        } else {
            return 0;
        }
    }

    /**
     * @inheritDoc
     */
    public $incrementing = FALSE;

    //endregion

    //region Protected Attributes

    /**
     * @inheritDoc
     */
    protected $appends = ['duration', 'total_seconds'];

    /**
     * @inheritDoc
     */
    protected $dates = ['ended_at', 'started_at'];

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'description', 'ended_at', 'id', 'project_id', 'started_at', 'task_id',
        'user_id', 'workspace_id'
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'user_id', 'workspace_id'
    ];

    /**
     * @inheritDoc
     */
    protected $keyType = 'string';

    /**
     * @inheritDoc
     */
    protected $visible = [
        'description', 'duration', 'ended_at', 'id', 'project_id', 'started_at',
        'task_id'
    ];

    //endregion
}
