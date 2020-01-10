<?php

Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@register');
Route::post('verification/resend', 'Auth\VerificationController@resend')
    ->name('verification.resend');
Route::get('email/verify', 'Auth\VerificationController@show')
    ->name('verification.notice');

Route::middleware('verified')->group(function () {
    Route::get('user', 'v1\UserController@self')
        ->name('users.self')
        ->middleware('auth');
});

