<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Payment Gateway
    |--------------------------------------------------------------------------
    |
    | This option controls the default payment gateway that will be used
    | by the system. You may set this to any of the connections defined
    | below. Supported: "midtrans", "tripay"
    |
    */
    'default' => env('PAYMENT_GATEWAY', 'midtrans'),

    'gateways' => [
        'midtrans' => [
            'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),
            'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
            'server_key' => env('MIDTRANS_SERVER_KEY', ''),
            'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        ],

        'tripay' => [
            'merchant_code' => env('TRIPAY_MERCHANT_CODE', ''),
            'api_key' => env('TRIPAY_API_KEY', ''),
            'private_key' => env('TRIPAY_PRIVATE_KEY', ''),
            'is_production' => env('TRIPAY_IS_PRODUCTION', false),
        ]
    ]
];
