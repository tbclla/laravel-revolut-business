<?php

namespace tbclla\Revolut\Auth\Requests;

use tbclla\Revolut\Client as RevolutClient;
use tbclla\Revolut\Auth\ClientAssertion;
use tbclla\Revolut\Interfaces\GrantsAccessTokens;
use tbclla\Revolut\Interfaces\MakesHttpRequests;

class AccessTokenRequest
{
    /**
     * The authentication endpoint
     * 
     * @var string
     */
    const ENDPOINT = '/auth/token';

    /**
     * The client assertion
     *
     * @var \tbclla\Revolut\Auth\ClientAssertion
     */
    private $clientAssertion;

    /**
     * The HTTP client
     *
     * @var \tbclla\Revolut\Interfaces\MakesHttpRequests
     */
    private $httpClient;

    /**
     * Create a new access token request instance
     * 
     * @param \tbclla\Revolut\Auth\ClientAssertion $clientAssertion
     * @param \tbclla\Revolut\Interfaces\MakesHttpRequests $httpClient
     * @return void
     */
    public function __construct(ClientAssertion $clientAssertion, MakesHttpRequests $httpClient)
    {
        $this->clientAssertion = $clientAssertion;
        $this->httpClient = $httpClient;
    }

    /**
     * Exchange an authorization code or a refresh token for an access token
     *
     * @param \tbclla\Revolut\Interfaces\GrantsAccessTokens $requestToken
     * @return array
     */
    public function exchange(GrantsAccessTokens $requestToken)
    {
        return $this->httpClient->post($this->uri(), [
            'form_params' => array_merge(
                $this->buildClientParams(),
                $this->buildGrantParams($requestToken)
            )
        ]);
    }

    /**
     * Get the Uri for the request
     * 
     * @return string
     */
    public static function uri()
    {
        return RevolutClient::buildUri(self::ENDPOINT);
    }

    /**
     * Build the client parameters
     * The request must inlude the client ID, the client assertion (JWT) and client assertion type
     * 
     * @return array
     */
    private function buildClientParams()
    {
        return [
            'client_assertion_type' => $this->clientAssertion::TYPE,
            'client_id' => $this->clientAssertion->clientId,
            'client_assertion' => $this->clientAssertion->build(),
        ];
    }

    /**
     * Build the grant parameters
     * 
     * @param \tbclla\Revolut\Interfaces\GrantsAccessTokens $requestToken
     * @return array
     */
    private function buildGrantParams(GrantsAccessTokens $requestToken)
    {
        return [
            'grant_type' => $requestToken->getGrantType(),
            $requestToken->getType() => $requestToken->getValue(),
        ];
    }
}
