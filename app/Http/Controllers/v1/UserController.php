<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CreateSubscriptionRequest;
use App\Repositories\Contracts\WorkspaceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //region Public Construction

    public function __construct(
        WorkspaceRepositoryInterface $workspaceRepository)
    {
        $this->workspaceRepository = $workspaceRepository;
    }

    //endregion

    //region Public Status Report

    /**
     * Add a payment method.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function addPaymentMethod(Request $request)
    {
        $user = Auth::user();
        $paymentMethod = $request->input('payment_method');
        $setAsDefault = $request->input('set_as_default', FALSE);

        if(!$paymentMethod) {
            return response([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'payment_method' => [
                        'The payment method is missing.'
                    ]
                ]
            ], 422);
        }

        if($user->stripe_id == NULL) {
            $user->createAsStripeCustomer();
            $user->refresh();
        }
        if($setAsDefault) {
            $cashierPaymentMethod = $user->updateDefaultPaymentMethod($paymentMethod);
        } else {
            $cashierPaymentMethod = $user->addPaymentMethod($paymentMethod);
        }

        return response('', 201);
    }

    /**
     * Create a subscription.
     *
     * @param CreateSubscriptionRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function createSubscription(CreateSubscriptionRequest $request)
    {
        $user = Auth::user();
        $workspace = $this->workspaceRepository->find($request->input('workspace_id'));
        if(!$workspace) {
            abort(404);
        }
        if($workspace->owner_user_id !== $user->id) {
            /*
             * Only the ownerUser can be subscribed to the workspace.
             */
            abort(403);
        }
        if($user->subscribed($workspace->id)) {
            return response([
                'data' => 'Already subscribed.',
                'errors' => [
                    'workspace_id' => [
                        'You are already subscribed to this workspace.'
                    ]
                ]
            ], 422);
        }

        $paymentMethod = $user->findPaymentMethod($request->input('stripe_id'));
        if(!$paymentMethod) {
            abort(404);
        }

        $user->newSubscription($workspace->id, config('pmtech.stripe.plan'))
            ->quantity($workspace->users()->count())
            ->create($request->input('stripe_id'));

        return response('', 201);
    }

    /**
     * Delete the payment passed in the $request parameters.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deletePaymentMethod(Request $request)
    {
        try {
            Auth::user()
                ->findPaymentMethod($request->input('payment_method_id'))
                ->delete();
        } catch(\Exception $e) {
            return response([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'payment_method_id' => [
                        $e->getMessage()
                    ]
                ]
            ], 422);
        }

        return response('', 205);
    }

    /**
     * Download the requested invoice.
     *
     * @param Request $request
     * @param $invoice
     * @return mixed
     */
    public function downloadInvoice(Request $request, $invoice)
    {
        return $request->user()->downloadInvoice($invoice, [
            'vendor' => 'ITPassion Ltd',
            'product' => 'Project-Management subscription'
        ]);
    }

    /**
     * List all invoices.
     *
     * @return Collection
     */
    public function invoices()
    {
        $invoices = Auth::user()->invoices();
        $result = new Collection();
        foreach($invoices as $invoice) {
            $result->push([
                'date' => $invoice->date()->toFormattedDateString(),
                'total' => $invoice->total(),
                'download' => route('user.downloadInvoice', [
                    'invoice' => $invoice->id
                ])
            ]);
        }

        return $result;
    }

    /**
     * Revoke any and all personal access tokens.
     *
     * @return string
     */
    public function logout()
    {
        Auth::user()->tokens->each->delete();

        return '';
    }

    /**
     * The authenticated user's payment methods from Cashier.
     *
     * @return mixed
     */
    public function paymentMethods()
    {
        $user = Auth::user();
        if($user->stripe_id) {
            $defaultPaymentMethod = $user->defaultPaymentMethod();
            if($defaultPaymentMethod) {
                $defaultPaymentMethodId = $defaultPaymentMethod
                    ->asStripePaymentMethod()
                    ->id;
            } else {
                $defaultPaymentMethodId = '';
            }

            $result = new Collection();

            foreach($user->paymentMethods() as $paymentMethod) {
                $stripePaymentMethod = $paymentMethod->asStripePaymentMethod();
                $result->push([
                    'card' => [
                        'brand' => $stripePaymentMethod->card->brand,
                        'exp_month' => $stripePaymentMethod->card->exp_month,
                        'exp_year' => $stripePaymentMethod->card->exp_year,
                        'last4' => $stripePaymentMethod->card->last4
                    ],
                    'default' =>
                        $defaultPaymentMethodId == $stripePaymentMethod->id,
                    'stripe_id' => $stripePaymentMethod->id,
                ]);
            }

            return $result;
        } else {
            return [];
        }
    }

    /**
     * The logged-in user
     *
     * @return mixed
     */
    public function self()
    {
        return Auth::user();
    }

    /**
     * Create a new setup intent for Cashier.
     *
     * @return mixed
     */
    public function setupIntent()
    {
        return Auth::user()->createSetupIntent();
    }

    //endregion

    //region Protected Attributes

    /**
     * The workspace repository.
     *
     * @var WorkspaceRepositoryInterface
     */
    protected $workspaceRepository;

    //endregion
}
