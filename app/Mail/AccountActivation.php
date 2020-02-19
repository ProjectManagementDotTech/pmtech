<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class AccountActivation extends Mailable
{
    use Queueable, SerializesModels;

    //region Public Construction

    /**
     * Create a new message instance.
     *
     * @return void
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->buttons = [
            [
                'href' => env('APP_URL') . 'email/verify/' . $user->id . '/' .
                    Cache::store('database')->get($user->email),
                'text' => 'Confirm my email address'
            ]
        ];
    }

    //endregion

    //region Public Status Report

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.authn.activation')->with([
            'buttons' => $this->buttons,
            'user' => $this->user
        ]);
    }

    /**
     * Get the user about whom this email is sent.
     *
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    //endregion

    //region Protected Attributes

    /**
     * @var array
     */
    protected $buttons;

    /**
     * @var User
     */
    protected $user;

    //endregion
}
