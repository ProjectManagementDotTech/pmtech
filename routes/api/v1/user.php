<?php

Route::post('verification/resend', 'Auth\VerificationController@resend')
    ->name('verification.resend');
Route::get('email/verify', 'Auth\VerificationController@show')
    ->name('verification.notice');

Route::middleware(['verified', 'auth:sanctum'])->group(function () {
    Route::get('user', 'v1\UserController@self')
        ->middleware('cache.headers:etag')
        ->name('user.self');
    Route::post('user/add-payment-method', 'v1\UserController@addPaymentMethod')
        ->name('user.addPaymentMethod');
    Route::post('user/create-subscription', 'v1\UserController@createSubscription')
        ->name('user.createSubscription');
    Route::post('user/delete-payment-method',
        'v1\UserController@deletePaymentMethod')
        ->name('user.deletePaymentMethod');
    Route::get('user/invoices', 'v1\UserController@invoices')
        ->name('user.invoices');
    Route::get('user/invoices/{invoice}', 'v1\UserController@downloadInvoice')
        ->name('user.downloadInvoice');
    Route::get('user/payment-methods', 'v1\UserController@paymentMethods')
        ->name('user.paymentMethods');
    Route::get('user/setup-intent', 'v1\UserController@setupIntent')
        ->name('user.setupIntent');
});

