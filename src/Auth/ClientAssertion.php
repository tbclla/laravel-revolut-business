<?php

namespace tbclla\Revolut\Auth;

use Exception;
use Firebase\JWT\JWT;
use tbclla\Revolut\Exceptions\ConfigurationException;

class ClientAssertion
{
    /**
     * The client assertion type
     * @link https://revolut-engineering.github.io/api-docs/business-api/#oauth-exchange-authorisation-code
     * 
     * @var string
     */
    const TYPE = 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer';

    /**
     * The JWT's audience parameter
     * @link https://revolut-engineering.github.io/api-docs/business-api/#authentication-setting-up-access-to-your-business-account
     * 
     * @var string
     */
    const AUDIENCE = 'https://revolut.com';

    /**
     * The JWT's algorythm parameter
     * @link https://revolut-engineering.github.io/api-docs/business-api/#authentication-setting-up-access-to-your-business-account
     * 
     * @var string
     */
    const ALGORYTHM = 'RS256';

    /**
     * The JWT client
     *
     * @var \firebase\JWT\JWT
     */
    private $jwtClient;

    /**
     * The client ID
     *
     * @var string
     */
    public $clientId;

    /**
     * The private key path
     *
     * @var string
     */
    private $privateKey;

    /**
     * The redirect URI
     *
     * @var string
     */
    private $redirectUri;

    /**
     * Create a new client assertion
     * 
     * @param string $clientId The client ID
     * @param string $privateKey The path to the private key
     * @param string $redirectUri The Oauth redirect URI
     * @return void
     */
    public function __construct(string $clientId, string $privateKey, string $redirectUri)
    {
        $this->jwtClient = new JWT;
        $this->clientId = $clientId;
        $this->privateKey = $privateKey;
        $this->redirectUri = $redirectUri;
    }

    /**
     * Build the JWT
     * 
     * @return string The assertion string
     * @throws \tbclla\Revolut\Exceptions\ConfigurationException
     */
    public function build()
    {
        try {
            return $this->jwtClient->encode($this->buildPayload(), $this->getPrivateKey(), self::ALGORYTHM);
        } catch (Exception $e) {
            throw new ConfigurationException('Failed to create JWT - ' . $e->getMessage(), null, $e);
        }
    }

    /**
     * Build the payload for the JWT
     * 
     * @return array
     */
    private function buildPayload()
    {
        return [
            'sub' => $this->clientId,
            'iss' => $this->getIssuer(),
            'exp' => self::getExpiration(),
            'aud' => self::AUDIENCE,
        ];
    }

    /**
     * Get the contents of the private key
     * 
     * @return string
     * @throws \tbclla\Revolut\Exceptions\ConfigurationException
     */
    private function getPrivateKey()
    {
        try {
            return file_get_contents($this->privateKey);
        } catch (Exception $e) {
            throw new ConfigurationException('Private Key not configured correctly! ' . $e->getMessage(), null, $e);
        }
    }

    /**
     * Get the JWT issuer
     * 
     * @return string
     * @throws \tbclla\Revolut\Exceptions\ConfigurationException
     */
    private function getIssuer()
    {
        $domain = parse_url($this->redirectUri);

        if (empty($domain['host'])) {
            throw new ConfigurationException('Invalid redirect URI.');
        }

        return $domain['host'];
    }

    /**
     * Get the expiration time in the form of a unix timestamp
     * 
     * @return int
     */
    private static function getExpiration()
    {
        return time() + (60 * 5);
    }
}
