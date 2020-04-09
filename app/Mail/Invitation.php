<?php

namespace App\Mail;

use App\Invitation as InvitationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class Invitation extends Mailable
{
    use Queueable, SerializesModels;

    //region Public Construct

    /**
     * Create a new message instance.
     *
     * @param InvitationModel $invitation
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(InvitationModel $invitation)
    {
        $appUrl = env('APP_URL');
        if($appUrl[strlen($appUrl) - 1] != '/') {
            $appUrl .= '/';
        }
        $this->invitation = $invitation;
        $this->buttons = [
            [
                'href' => $appUrl . 'invitation/accept/' .
                    $invitation->nonce . '/' .
                    Cache::store('database')->get($invitation->email),
                'text' => 'Accept invitation'
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
        return $this->view('emails.authn.invitation')->with([
            'buttons' => $this->buttons,
            'invitation' => $this->invitation
        ]);
    }

    /**
     * The user to whom the invitation email is send.
     *
     * @return InvitationModel
     */
    public function invitation(): InvitationModel
    {
        return $this->invitation;
    }

    //endregion

    //region Protected Attributes

    /**
     * @var array
     */
    protected $buttons;

    /**
     * @var InvitationModel
     */
    protected $invitation;

    //endregion
}
