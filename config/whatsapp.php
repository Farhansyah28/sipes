<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default WhatsApp Gateway
    |--------------------------------------------------------------------------
    |
    | Supported: "wablas", "fonnte", "ruangwa"
    |
    */
    'default' => env('WA_GATEWAY', 'fonnte'),

    'gateways' => [
        'wablas' => [
            'domain' => env('WABLAS_DOMAIN', 'https://domain.wablas.com'),
            'api_token' => env('WABLAS_TOKEN', ''),
        ],

        'fonnte' => [
            'api_token' => env('FONNTE_TOKEN', ''),
        ],
        
        'ruangwa' => [
            'api_token' => env('RUANGWA_TOKEN', ''),
        ]
    ]
];
