@extends('layouts.base')

@section('body')
    <header>
        <div>Logo</div>
        <div>Menu</div>
    </header>
    <main>
        @yield('content')
    </main>
    <footer>Footer</footer>
@endsection
