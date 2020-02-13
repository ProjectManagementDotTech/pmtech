@extends('emails._layouts.email')

@section('title', 'Registration Confirmation')
@section('subject', 'Thank you for your registration')
@section('bodycopy')
    <h2>Hello {{ $user->name }},</h2>
    <p>
        Welcome to project-management.tech, the online solution for all
        project management tasks.
    </p>
    <p>
        We hope you'll enjoy project-management.tech and the tools we provide to
        you and your organization, however before you can enjoy it, we would
        like to make sure that the email address is valid.
    </p>
    <p>
        Please click the button below to confirm that your email address is
        valid.
    </p>
@endsection
