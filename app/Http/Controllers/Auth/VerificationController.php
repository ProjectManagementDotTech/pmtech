<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Repositories\SettingsRepository;
use App\Repositories\WorkspaceRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function resend(Request $request)
    {
        $user = User::query()
            ->where('email', $request->get('email', NULL))->first();
        if($user) {
            if($user->hasVerifiedEmail()) {
                return response([
                    'message' => 'The email address was already validated.',
                    'errors' => [
                        'email' => [
                            'The email address was already validated.'
                        ]
                    ]
                ], 422);
            } else {
                Cache::store('database')->pull($request->email);
                Cache::store('database')
                    ->put($request->email, Uuid::uuid4()->toString(), 3600);
                $user->sendEmailVerificationNotification();
                return response('', 201);
            }
        } else {
            return response([
                'message' => 'The email address is unknown.',
                'errors' => [
                    'email' => [
                        'The email address is unknown.'
                    ]
                ]
            ], 422);
        }
    }

    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));
        if($user) {
            $cacheHash = Cache::store('database')->get($user->email);
            if($cacheHash && $cacheHash == $request->route('hash')) {
                Cache::store('database')->pull($user->email);
                $user->email_verified_at = Carbon::now();
                $user->save();

                WorkspaceRepository::create([
                    'owner_user_id' => $user->id,
                    'name' => 'Default'
                ]);
                SettingsRepository::create($user);

                return redirect('/login');
            }
        }

        throw new AuthorizationException();
    }
}
