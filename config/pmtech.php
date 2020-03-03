<?php

return [

    /*
     * Whether or not this installation takes payments at all. If set to FALSE,
     * the whole payment / subscription logic will be by-passed. If set to TRUE,
     * please fill out the charge_tiers array as well; make sure that the tiers
     * you describe below match your Stripe Subscription Plans.
     */
    'charging' => TRUE,
    'charge_tiers' => [
        'Free' => [
            'min' => 0,
            'max' => 5,
            'seat_charge' => [
                'monthly' => 0,
                'yearly' => 0,
            ],
        ],
        'Paid' => [
            'min' => 6,
            'seat_charge' => [
                'monthly' => 499,
                'yearly' => 4990
            ]
        ]
    ],

    /*
     * The Plan ID of the monthly recurring payment within stripe.
     */
    'stripe' => [
        'plan' => env('PMTECH_STRIPE_PLAN'),
    ],


];
