<form method="POST" action="{{ route('register') }}">
    @csrf
    Name: <input id="name" type="text" name="name"><br />
    @error('name')
    {{ $message }}<br />
    @enderror
    Email Address: <input id="email" type="email" name="email"><br />
    @error('email')
    {{ $message }}<br />
    @enderror
    Password: <input id="password" type="password" name="password"><br />
    @error('password')
    {{ $message }}<br />
    @enderror
    Confirm password: <input id="password-confirm" type="password" name="password_confirmation"><br />
    <button type="submit">Register</button>
</form>
