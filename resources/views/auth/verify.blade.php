@if(session('resent'))
    <div>
        A fresh verification link has been sent to your email address.
    </div>
@endif

Before proceeding, please check your email for a verification link.
If you did not receive the email,
<form method="POST" action="{{ route('verification.resend') }}">
    @csrf
    <button type="submit">click here to request another</button>.
</form>
