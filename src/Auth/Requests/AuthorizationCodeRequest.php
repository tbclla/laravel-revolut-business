<?php

namespace tbclla\Revolut\Auth\Requests;

class AuthorizationCodeRequest
{
    /**
     * The sandbox URL
     * 
     * @var string
     */
    const SANDBOX_URL = 'https://sandbox-business.revolut.com';

    /**
     * The production URL
     * 
     * @var string
     */
    const PRODUCTION_URL = 'https://business.revolut.com';

    /**
     * The app authorization endpoint
     * 
     * @var string
     */
    const ENDPOINT = '/app-confirm';

    /**
     * A token repository
     *
     * @var string The client Id
     */
    private $clientId;

    /**
     * A token repository
     *
     * @var string The redirect URI
     */
    private $redirectUri;

    /**
     * A token repository
     *
     * @var bool The environment
     */
    private $sandbox;

    /**
     * A state value
     *
     * @var string
     */
    public $state;

    /**
     * Create a new request
     *
     * @param string $clientId The Revolut Business Client ID
     * @param string $redirectUri The OAuth redirect URI
     * @param bool $sandbox Whether or not to use the sandbox environment
     * @return void
     */
    public function __construct(string $clientId, string $redirectUri, bool $sandbox = true)
    {
        $this->clientId = $clientId;
        $this->redirectUri = $redirectUri;
        $this->sandbox = $sandbox;
        $this->state = $this->generateState();
    }

    /**
     * Build the request
     * 
     * @return string
     */
    public function build()
    {
        return $this->baseUri() . self::ENDPOINT . '?' . $this->buildQuery();
    }

    /**
     * Build the base URI
     * 
     * @return string
     */
    private function baseUri()
    {
        return $this->sandbox ? self::SANDBOX_URL : self::PRODUCTION_URL;
    }

    /**
     * Build the query
     * 
     * @return string
     */
    private function buildQuery()
    {
        return http_build_query([
            'response_type' => 'request_token',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'state' => $this->state
        ]);
    }

    /**
     * Generate a state value
     *
     * @return string
     */
    private function generateState()
    {
        return base64_encode(random_bytes(32));
    }
}
