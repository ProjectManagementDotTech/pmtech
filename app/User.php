<?php

namespace App;

use App\Mail\AccountActivation;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Billable, Notifiable, SoftDeletes;

    //region Public Access

    /**
     * Attach $workspace to the user's workspaces, and update $workspace's
     * subscription information.
     *
     * @param Workspace $workspace
     */
    public function attachToWorkspace(Workspace $workspace)
    {
        $this->workspaces()->attach($workspace);
        $workspace->updateSubscriptionInformation();
    }

    /**
     * Detach $this User from $workspace, and update $workspace's subscription
     * information.
     *
     * @param Workspace $workspace
     */
    public function detachFromWorkspace(Workspace $workspace)
    {
        $this->workspaces()->detach($workspace);
        $workspace->updateSubscriptionInformation();
    }

    /**
     * @inheritDoc
     */
    public function sendEmailVerificationNotification()
    {
        Mail::to($this)->send(new AccountActivation($this));
    }

    //endregion

    //region Public Relationships

    /**
     * All the workspaces owned by this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownedWorkspaces()
    {
        return $this->hasMany(Workspace::class, 'owner_user_id');
    }

    /**
     * The projects to which this user belongs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    /**
     * The settings for this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function settings()
    {
        return $this->hasOne(Settings::class);
    }

    /**
     * The timesheet entries that belong to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timesheetEntries()
    {
        return $this->hasMany(TimesheetEntry::class);
    }

    /**
     * The workspaces to which this user belongs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class);
    }

    //endregion

    //region Public Status Report

    /**
     * @inheritDoc
     */
    public $incrementing = FALSE;

    //endregion

    //region Protected Attributes

    /**
     * @inheritDoc
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'email', 'email_verified_at', 'id', 'name', 'password',
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'created_at', 'deleted_at', 'email_verified_at', 'password',
        'remember_token', 'updated_at',
    ];

    /**
     * @inheritDoc
     */
    protected $keyType = 'string';

    /**
     * @inheritDoc
     */
    protected $visible = [
        'id', 'email', 'name', 'settings',
    ];

    /**
     * @inheritDoc
     */
    protected $with = [
        'settings'
    ];

    //endregion
}
