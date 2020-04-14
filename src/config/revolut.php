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
    'tokens_table' => 'revolut_tokens'
];
