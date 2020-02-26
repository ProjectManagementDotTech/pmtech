<?php

namespace App\Mail\Payment;

use App\Workspace;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class FirstTime extends Mailable
{
    use Queueable, SerializesModels;

    //region Public Construction

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Workspace $workspace)
    {
        $appUrl = env('APP_URL');
        if($appUrl[strlen($appUrl) - 1] != '/') {
            $appUrl .= '/';
        }
        $this->buttons = [
            [
                'href' => $appUrl . 'user/' . $workspace->owner_user_id .
                    '/settings/payment',
                'text' => 'Pay subscription fee'
            ]
        ];
        $this->workspace = $workspace;
        $this->calculateSubscriptionFee();
    }

    //endregion

    //region Public Access

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.payments.first_time');
    }

    //endregion

    //region Public Attributes

    /**
     * @var float
     */
    public $subscriptionFee;

    /**
     * @var Workspace
     */
    public $workspace;

    //endregion

    //region Private Implementation

    /**
     * Calculate the subscription fee.
     */
    private function calculateSubscriptionFee()
    {
        $count = $this->workspace->users()->count() - 5;
        if($count > 0) {
            $this->subscriptionFee = $count * 4.99;
        } else {
            $this->subscriptionFee = 0;
        }
    }

    //endregion
}
