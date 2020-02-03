<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    use AuthenticatesUsers {
        login as parentLogin;
    }

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

    public function login(Request $request)
    {
        $user = UserRepository::byEmail($request->input('email', NULL));
        if($user) {
            if($user->hasVerifiedEmail()) {
                return $this->parentLogin($request);
            }
        }

        abort(403, "Your email address is not verified.");
    }

    /**
     * @inheritDoc
     */
//    protected function authenticated(Request $request, $user)
//    {
//        $token = $user->createToken('VueSPA');
//        return [
//            'access_token' => $token->plainTextToken,
//            'token_type' => 'Bearer'
//        ];
//    }
}
