<form method="POST" action="{{ route('login') }}">
    @csrf
    Email address: <input type="email" id="email" name="email" /><br />
    @error('email')
    {{ $message }}
    @enderror
    Password: <input type="password" id="password" name="password" /><br />
    @error('password')
    {{ $message }}
    @enderror
    <button type="submit">Login</button>
</form>


