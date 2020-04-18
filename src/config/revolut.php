<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | Define whether or not to run in sandbox mode.
    | Default is true.
    |
    */
    'sandbox' => env('REVOLUT_SANDBOX', true),

    /*
    |--------------------------------------------------------------------------
    | Private Key Path
    |--------------------------------------------------------------------------
    |
    | Set the path to your private key.
    | You must supply the corresponding public key to Revolut during the
    | authorization process.
    |
    */
    'private_key' => env('REVOLUT_PRIVATE_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Client ID
    |--------------------------------------------------------------------------
    |
    | Your client ID is provided by Revolut.
    | You can find this by visiting your account Settings > API and then
    | selecting the relevant API certificate.
    |
    */
    'client_id' => env('REVOLUT_CLIENT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Oauth Redirect URI
    |--------------------------------------------------------------------------
    |
    | Set where Revoult should redirect you at the end of the authorization 
    | process. This must match the uri provided to Revolut.
    |
    | You do NOT have to create your own web route, this package creates the
    | necessary route and controllers.
    |
    */
    'redirect_uri' => env('REVOLUT_REDIRECT_URI'),

    /*
    |--------------------------------------------------------------------------
    | Tokens
    |--------------------------------------------------------------------------
    |
    | Configue where and how Revolut's access and refresh tokens are stored.
    |
    | When using the 'database' store, an additonal migration for the defined
    | table will be included automatically whenever you run your migrations.
    |
    | When using the 'cache' store, a cache driver may be specified. If the
    | cache driver is set to null, the default driver defined in Laravel's
    | 'config/cache.php' will be used.
    | 
    | Supported stores: 'database', 'cache'
    |
    */
    'tokens' => [

        'encrypt' => true,

        'store' => 'database',

        'database' => [
            'table_name' => 'revolut_tokens'
        ],

        'cache' => [
            'driver' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Authorization Route
    |--------------------------------------------------------------------------
    |
    | Configure the authorization route, which is used to initate Revolut's
    | authorization process. 
    | The 'web' middleware is required for sessions to work reliably and is
    | therefore applied automatically and does not need to be specified
    |
    */
    'auth_route' => [

        'name' => 'revolut-authorization',
        
        'middleware' => [
            // 'auth'
        ]
    ],
];
