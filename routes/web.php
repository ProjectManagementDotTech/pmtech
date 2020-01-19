<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
\Illuminate\Support\Facades\Auth::routes(['verify' => TRUE]);
+--------+----------+--------------------------+---------------------+------------------------------------------------------------------+------------------------------+
| Domain | Method   | URI                      | Name                | Action                                                           | Middleware                   |
+--------+----------+--------------------------+---------------------+------------------------------------------------------------------+------------------------------+
|        | POST     | email/resend             | verification.resend | App\Controller\Auth\VerificationController@resend                | web,auth,throttle:6,1        |
|        | GET|HEAD | email/verify             | verification.notice | App\Controller\Auth\VerificationController@show                  | web,auth                     |
|        | GET|HEAD | email/verify/{id}/{hash} | verification.verify | App\Controller\Auth\VerificationController@verify                | web,auth,signed,throttle:6,1 |
|        | GET|HEAD | login                    | login               | App\Controller\Auth\LoginController@showLoginForm                | web,guest                    |
|        | POST     | login                    |                     | App\Controller\Auth\LoginController@login                        | web,guest                    |
|        | POST     | logout                   | logout              | App\Controller\Auth\LoginController@logout                       | web                          |
|        | GET|HEAD | password/confirm         | password.confirm    | App\Controller\Auth\ConfirmPasswordController@showConfirmForm    | web,auth                     |
|        | POST     | password/confirm         |                     | App\Controller\Auth\ConfirmPasswordController@confirm            | web,auth                     |
|        | POST     | password/email           | password.email      | App\Controller\Auth\ForgotPasswordController@sendResetLinkEmail  | web                          |
|        | GET|HEAD | password/reset           | password.request    | App\Controller\Auth\ForgotPasswordController@showLinkRequestForm | web                          |
|        | POST     | password/reset           | password.update     | App\Controller\Auth\ResetPasswordController@reset                | web                          |
|        | GET|HEAD | password/reset/{token}   | password.reset      | App\Controller\Auth\ResetPasswordController@showResetForm        | web                          |
|        | GET|HEAD | register                 | register            | App\Controller\Auth\RegisterController@showRegistrationForm      | web,guest                    |
|        | POST     | register                 |                     | App\Controller\Auth\RegisterController@register                  | web,guest                    |
|        | GET|HEAD | {any?}                   |                     | Closure                                                          | web                          |
+--------+----------+--------------------------+---------------------+------------------------------------------------------------------+------------------------------+
*/

use Illuminate\Support\Facades\Cache;

Route::get('/activation-mailable', function () {
    phpinfo();
    exit(0);
    $user = \App\User::find('443cb036-8048-4d97-bebf-b846f10dcb45');
    $buttons = [
        [
            'href' => env('APP_URL') . 'email/verify/' .
                $user->id . '/' .
                Cache::store('database')->get($user->email),
            'text' => 'Confirm my email address'
        ]
    ];
    return view('emails.authn.activation')->with([
        'buttons' => $buttons,
        'user' => $user
    ]);
});

Route::post('register', 'Auth\RegisterController@register');
Route::post('logout', 'Auth\LoginController@logout');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')
    ->name('verification.verify');

/*
 * If nothing matches, we simply load the SPA app and let it deal with the
 * request...
 */
Route::fallback(function () {
    return view('app');
});
