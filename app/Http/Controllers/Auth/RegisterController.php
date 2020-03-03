<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AccountActivation;
use App\Providers\RouteServiceProvider;
use App\Repositories\UserRepository;
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
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * @inheritDoc
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $registeredUser = UserRepository::byEmail($request->email);
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

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     * @throws \Exception
     */
    protected function create(array $data): User
    {
        return UserRepository::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
