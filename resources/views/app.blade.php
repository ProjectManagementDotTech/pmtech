@extends('layouts.base')

@section('title', 'Welcome')

@section('body')
    <div id="app">
        <navigation></navigation>
        <main>
            <router-view></router-view>
        </main>
        <pmtech-footer></pmtech-footer>
    </div>
    <script src="/js/app.js"></script>
@endsection
