<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Repositories\Contracts\SettingsRepository as
    SettingsRepositoryInterface;
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
    use VerifiesEmails;

    //region Public Construction

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SettingsRepositoryInterface $settingsRepository)
    {
        $this->middleware('guest');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
        $this->settingsRepository = $settingsRepository;
    }

    //endregion

    //region Public Status Report

    /**
     * Resend the activation link.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
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

                return redirect('/login');
            }
        }

        throw new AuthorizationException();
    }

    //region Protected Attributes

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @var SettingsRepository
     */
    protected $settingsRepository;

    //endregion
}
