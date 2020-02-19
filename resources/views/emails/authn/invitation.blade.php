@extends('emails._layouts.email')

@section('subject', 'Invitation to collaborate in workspace')
@section('bodycopy')
    <h2>Hello,</h2>
    <p>
        {{ $invitation->user->name }} has invited you to collaborate in the
        workspace "{{ $invitation->workspace->name }}".
    </p>
    <p>
        This invitation is valid for 24 hours. A new invitation needs to be send
        should this be too short a period.
    </p>
    <p>
        Please click the button below to accept the invitation.
    </p>
@endsection
