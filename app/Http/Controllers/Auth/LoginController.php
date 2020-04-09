<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers {
        login as parentLogin;
    }

    //region Public Construction

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware('guest')->except('logout');
    }

    //endregion

    //region Public Status Report

    /**
     * @inheritDoc
     */
    public function login(Request $request)
    {
        $user = $this->userRepository
            ->findByEmail($request->input('email', NULL));
        if($user) {
            if($user->hasVerifiedEmail()) {
                return $this->parentLogin($request);
            }
        }

        abort(403, "Your email address is not verified.");
    }

    //region Protected Attributes

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * The user repository.
     *
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    //endregion
}
