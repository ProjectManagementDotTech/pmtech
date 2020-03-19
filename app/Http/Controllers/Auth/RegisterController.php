<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AccountActivation;
use App\Providers\RouteServiceProvider;
use App\Repositories\Contracts\UserRepository;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class RegisterController extends Controller
{
    use RegistersUsers;

    //region Public Construction

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        $this->middleware('guest');
    }

    //endregion

    //region Public Status Report

    /**
     * @inheritDoc
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $registeredUser = $this->userRepository->findByEmail($request->email);
        if($registeredUser) {
            if($registeredUser->hasVerifiedEmail()) {
                return response([
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'email' => [
                            'The email has already been taken.'
                        ]
                    ]
                ], 422);
            } else {
                Cache::store('database')
                    ->put($registeredUser->email, Uuid::uuid4()->toString(),
                        3600);
                Mail::to($registeredUser)
                    ->send(new AccountActivation($registeredUser));
                return response('', 200);
            }
        }

        /**
         * Generate a cache entry so the user can verify his/her email address.
         */
        Cache::store('database')
            ->put($request->email, Uuid::uuid4()->toString(), 3600);

        event(new Registered($user = $this->create($request->all())));

        return response('', 200);
    }

    //endregion

    //region Protected Attributes

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * The user repository.
     *
     * @var UserRepository
     */
    protected $userRepository;

    //endregion

    //region Protected Implementation

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     * @throws \Exception
     */
    protected function create(array $data): User
    {
        return $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    //endregion
}
