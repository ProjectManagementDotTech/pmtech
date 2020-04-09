<?php

namespace App;

use App\Mail\Payment\FirstTime;
use App\Traits\Models\SupportsETags;
use IgnitionNbs\LaravelUuidModel\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

class Workspace extends Model
{
    use SoftDeletes, SupportsETags, UuidModel;

    //region Public Access

    /**
     * Update subscription information by updating the relevant information in
     * owner user's subscription model.
     * Sent a notification to the owner user to indicate that subscription
     * payments become necessary. Currently, this will be done via email, but
     * that may change to email *and* notification ot to notification only.
     */
    public function updateSubscriptionInformation()
    {
        $count = $this->users()->count();
        $ownerUser = $this->ownerUser;

        if($count > 5 && !$ownerUser->subscribed($this->id)) {
            $ownerUser->createOrGetStripeCustomer();
            Mail::to($ownerUser)->send(
                new FirstTime($this));
        } elseif($ownerUser->subscribed($this->id)) {
            $ownerUser->subscription($this->id)->updateQuantity($count);
        }
    }

    //endregion

    //region Public Relationships

    /**
     * The clients that belong to this workspace.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    /**
     * The user who owns this workspace.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * The projects that belong to this workspace.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * The timesheet entries that belong to this workspace.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timesheetEntries()
    {
        return $this->hasMany(TimesheetEntry::class);
    }

    /**
     * The users that belong to this workspace.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    //endregion

    //region Public Status Report

    /**
     * @inheritDoc
     */
    public $incrementing = FALSE;

    /**
     * The subscription fee for this workspace.
     *
     * @return float
     */
    public function subscriptionFee()
    {
        $count = $this->users()->count() - 5;
        return $count > 0 ? $count * 4.99 : 0;
    }

    //endregion

    //region Protected Attributes

    protected $fillable = [
        'id', 'owner_user_id', 'name'
    ];

    protected $hidden = [
        'created_at', 'deleted_at', 'updated_at'
    ];

    /**
     * @inheritDoc
     */
    protected $keyType = 'string';

    /**
     * @inheritDoc
     */
    protected $visible = [
        'id', 'name', 'owner_user_id'
    ];

    //endregion
}
