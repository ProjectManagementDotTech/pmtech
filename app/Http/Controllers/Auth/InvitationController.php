<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StoreDetailsRequest;
use App\Repositories\InvitationRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class InvitationController extends Controller
{
    //region Public Construction

    /**
     * InvitationController constructor.
     */
    public function __construct(InvitationRepository $invitationRepository)
    {
        $this->invitationRepository = $invitationRepository;

        $this->middleware('guest');
        $this->middleware('throttle:6,1');
    }

    //endregion

    //region Public Status Report

    /**
     * Accept the given invitation, and redirect to user to an input screen
     * where name and password can be given.
     *
     * @param string $invitationNonce
     * @param string $cacheNonce
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function accept(string $invitationNonce, string $cacheNonce)
    {
        $valid = $this->validateNonces($invitationNonce, $cacheNonce);

        return $valid === TRUE ? view('app') : $valid;
    }

    /**
     * Create a new User model and add the new user as a member of the workspace
     * the user was invited to.
     *
     * @param StoreDetailsRequest $request
     * @param string $invitationNonce
     * @param string $cacheNonce
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function storeDetails(StoreDetailsRequest $request,
         string $invitationNonce, string $cacheNonce)
    {
        $valid = $this->validateNonces($invitationNonce, $cacheNonce);
        if($valid === TRUE) {
            $invitation = $this->invitationRepository
                ->byNonce($invitationNonce);
            $user = UserRepository::create([
                'email' => $invitation->email,
                'email_verified_at' => Carbon::now(),
                'name' => $request->name,
                'password' => Hash::make($request->password)
            ]);
            $user->attachToWorkspace($invitation->workspace);
            Cache::store('database')->pull($invitation->email);
            $invitation->delete();
            return redirect('/login');
        } else {
            return $valid;
        }
    }

    //endregion

    //region Protected Attributes

    /**
     * @var InvitationRepository
     */
    protected $invitationRepository;

    //endregion

    //region Protected Implementation

    /**
     * Are both nonces valid?
     *
     * @param string $invitationNonce
     * @param string $cacheNoce
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function validateNonces(string $invitationNonce, string $cacheNonce)
    {
        $invitation = $this->invitationRepository->byNonce($invitationNonce);
        if($invitation) {
            $cacheValue = Cache::store('database')->pull($invitation->email);
            if($cacheValue == $cacheNonce) {
                return TRUE;
            }
        }

        return response('Unauthorized.', 403);
    }

    //endregion
}
