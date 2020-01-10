<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @inheritDoc
     */
    public function login(Request $request)
    {
        Log::info(__METHOD__);
        $user = User::where('email', $request->email)->first();
        if($user && $user->email_verified_at !== NULL) {
            $credentials = request(['email', 'password']);
            if(!$token = auth()->attempt($credentials)) {
                Log::debug('sendFailedLoginResponse');
                return $this->sendFailedLoginResponse($request);
            } else {
                Log::debug('sendTokenResponse');
                return $this->sendTokenResponse($token);
            }
        } else {
            Log::debug('Your email address is not verified.');
            return response([
                'message' => 'Your email address is not verified.'
            ], 403);
        }
    }

    /**
     * Craft a response based on the given token.
     *
     * @param string $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function sendTokenResponse(string $token)
    {
        return response([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    }
}
