@extends('emails._layouts.email')

@section('title', 'Setup Subscription Payment')
@section('bodycopy')
    @php
        $currencyFormatter = new \NumberFormatter('en_IE', \NumberFormatter::CURRENCY);
    @endphp
    <h2>Hello {{ $workspace->ownerUser->name }},</h2>
    <p>
        The workspace "{{ $workspace->name }}" that you own, now has
        {{ $workspace->users()->count() }} users.
    </p>
    <p>
        This means that services provided are subject to a subscription fee.
        Based on the amount of users currently in the workspace, that
        subscription fee is {{ $currencyFormatter->formatCurrency($subscriptionFee, 'EUR') }}.
    </p>
@endsection
