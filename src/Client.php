<?php

namespace tbclla\Revolut;

use Illuminate\Support\Str;
use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Exceptions\RevolutException;
use tbclla\Revolut\Interfaces\MakesHttpRequests;

/**
 * @method \tbclla\Revolut\Resources\Account account()
 * @method \tbclla\Revolut\Resources\Exchange exchange()
 * @method \tbclla\Revolut\Resources\Payment payment()
 * @method \tbclla\Revolut\Resources\PaymentDraft paymentDraft()
 * @method \tbclla\Revolut\Resources\Rate rate()
 * @method \tbclla\Revolut\Resources\Transaction transaction()
 * @method \tbclla\Revolut\Resources\Transfer transfer()
 * @method \tbclla\Revolut\Resources\Webhook webhook()
 */
class Client
{
    /**
     * The production URL
     * 
     * @var string
     */
    const PRODUCTION_URL = 'https://b2b.revolut.com';

    /**
     * The sandbox URL
     * 
     * @var string
     */
    const SANDBOX_URL = 'https://sandbox-b2b.revolut.com';

    /**
     * The API URI
     * 
     * @var string
     */
    const API_ENDPOINT = '/api';

    /**
     * The API version
     * 
     * @var string
     */
    const API_VERSION = '1.0';

    /**
     * The token manager
     *
     * @var \tbclla\Revolut\Auth\TokenManager
     */
    private $tokenManager;

    /**
     * the HTTP client
     * 
     * @var \tbclla\Revolut\Interfaces\MakesHttpRequests
     */
    private $httpClient;

    /**
     * The access token
     * 
     * @var \tbclla\Revolut\Auth\AccessToken
     */
    private $accessToken;

    /**
     * Create the client instance
     * 
     * @param \tbclla\Revolut\Auth\TokenManager $tokenManager
     * @param \tbclla\Revolut\Interfaces\MakesHttpRequests $httpClient
     * @return void
     */
    public function __construct(TokenManager $tokenManager, MakesHttpRequests $httpClient)
    {
        $this->tokenManager = $tokenManager;
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $name
     * @param mixed $arguments
     * @return \tbclla\Revolut\Resources\Resource
     * @throws \tbclla\Revolut\Exceptions\RevolutException
     */
    public function __call($name, $arguments)
    {
        $resource = __NAMESPACE__ . '\\Resources\\' . ucfirst($name);
        if (!class_exists($resource)) {
            throw new RevolutException($resource . ' is not a valid API resource');
        }
        return new $resource($this);
    }

    /**
     * Set the access token
     *
     * @param \tbclla\Revolut\Auth\AccessToken $accessToken
     * @return void
     */
    public function setAccessToken(AccessToken $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Create and set a fresh access token
     * 
     * @return void
     */
    public function refreshAccessToken(): void
    {
        $this->setAccessToken($this->tokenManager->refreshAccessToken());
    }

    /**
     * Get the base URI for all API requests
     * 
     * @param string $endpoint
     * @return string
     */
    public static function buildUri(string $endpoint = '')
    {
        $url = config('revolut.sandbox', true) ? self::SANDBOX_URL : self::PRODUCTION_URL;

        return $url . self::apiUri() . $endpoint;
    }
    
    /**
     * Get the URI for API requests
     * 
     * @return string
     */
    public static function apiUri()
    {
        return self::API_ENDPOINT . '/' . self::API_VERSION;
    }

    /**
     * Perform a POST request against a specified endpoint
     *
     * @param string $endpoint
     * @param array $options
     * @return array The response body
     * @throws \tbclla\Revolut\Exceptions\ApiException if the client responded with a 4xx-5xx response
     * @throws \tbclla\Revolut\Exceptions\AppUnauthorizedException if the app needs to be re-authorized
     */
    public function post(string $endpoint, array $options = [])
    {
        return $this->httpClient->post($this->buildUri($endpoint), $this->buildOptions($options));
    }

    /**
     * Perform a GET request against a specified endpoint
     *
     * @param string $endpoint
     * @return array The response body
     * @throws \tbclla\Revolut\Exceptions\ApiException if the client responded with a 4xx-5xx response
     * @throws \tbclla\Revolut\Exceptions\AppUnauthorizedException if the app needs to be re-authorized
     */
    public function get(string $endpoint, array $options = [])
    {
        return $this->httpClient->get($this->buildUri($endpoint), $this->buildOptions($options));
    }

    /**
     * Perform a DELETE request against a specified endpoint
     *
     * @param string $endpoint
     * @return void
     * @throws \tbclla\Revolut\Exceptions\ApiException if the client responded with a 4xx-5xx response
     * @throws \tbclla\Revolut\Exceptions\AppUnauthorizedException if the app needs to be re-authorized
     */
    public function delete(string $endpoint)
    {
        $this->httpClient->delete($this->buildUri($endpoint), $this->buildOptions());
    }

    /**
     * Build the request options
     * 
     * @param array $options
     * @return array
     */
    private function buildOptions(array $options = [])
    {
        if (!$this->accessToken) {
            $this->setAccessToken($this->tokenManager->getAccessToken());
        } else if ($this->accessToken->hasExpired()) {
            $this->refreshAccessToken();
        }

        return array_merge($options, ['headers' => ['Authorization' => 'Bearer ' . $this->accessToken->value]]);
    }

    /**
     * Generate a v4 UUID to use as a request ID
     * 
     * @return string
     */
    public static function generateRequestId()
    {
        return (string) Str::Uuid();
    }
}
