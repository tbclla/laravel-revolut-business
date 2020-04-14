<?php

namespace tbclla\Revolut\Auth\Requests;

use tbclla\Revolut\Repositories\TokenRepository;

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
	 * @var \tbclla\Revolut\Repositories\TokenRepository
	 */
	private $tokens;

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
	 * Create a new request
	 *
	 * @param \tbclla\Revolut\Repositories\TokenRepository $tokens
	 * @param string $clientId The Revolut Business Client ID
	 * @param string $redirectUri The OAuth redirect URI
	 * @param bool $sandbox Whether or not to use the sandbox environment
	 * @return void
	 */
	public function __construct(TokenRepository $tokens, string $clientId, string $redirectUri, bool $sandbox = true)
	{
		$this->tokens = $tokens;
		$this->clientId = $clientId;
		$this->redirectUri = $redirectUri;
		$this->sandbox = $sandbox;
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
			'state' => $this->makeState(),
		]);
	}

	/**
	 * Create a new state token
	 *
	 * @return string The state token value
	 */
	private function makeState()
	{
		$this->tokens->createState($value = uniqid());
		return $value;
	}
}
