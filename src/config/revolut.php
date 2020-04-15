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
    | Encryption
    |--------------------------------------------------------------------------
    |
    | Define whether or not to encrypt tokens before storing them.
    | It is recommended to keep the encryption turned on, especially in the
    | production environment
    |
    */
    'encrypt_tokens' => true,

    /*
    |--------------------------------------------------------------------------
    | Tokens table
    |--------------------------------------------------------------------------
    |
    | Set the name of the table that will hold your Revolut tokens.
    |
    */
    'tokens_table' => 'revolut_tokens',

    /*
    |--------------------------------------------------------------------------
    | Authorization Route
    |--------------------------------------------------------------------------
    |
    | Configure the authorization route, which is used to initate Revolut's
    | authorization process.
    |
    | The url is '<redirect_uri>/create'.
    |
    | You can set a name for this route, and define the middleware for it.
    | It is recommended that you secure this route with middleware to
    | prevent unauthorized persons from initiating the Oauth flow.
    |
    */
    'auth_route' => [

        'name' => 'revolut-authorization',

        /**
         * The 'web' middleware is required for sessions to work reliably, as such
         * it is set automatically and does not need to be specified.
         */
        'middleware' => [
            // 'auth'
        ]
    ],
];
