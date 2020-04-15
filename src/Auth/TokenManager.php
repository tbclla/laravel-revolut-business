<?php

namespace tbclla\Revolut\Auth;

use tbclla\Revolut\Auth\Requests\AccessTokenRequest;
use tbclla\Revolut\Exceptions\RevolutException;
use tbclla\Revolut\Interfaces\GrantsAccessTokens;
use tbclla\Revolut\Repositories\TokenRepository;

class TokenManager
{
	/**
	 * The token repository
	 *
	 * @var \tbclla\Revolut\Repositories\TokenRepository
	 */
	private $tokenRepository;

	/**
	 * The access token request
	 *
	 * @var \tbclla\Revolut\Auth\Requests\AccessTokenRequest
	 */
	private $accessTokenRequest;

	/**
	 * Create a token manager
	 *
	 * @param \tbclla\Revolut\Repositories\TokenRepository $tokenRepository
	 * @param \tbclla\Revolut\Auth\Requests\AccessTokenRequest $accessTokenRequest
	 * @return void
	 */
	public function __construct(TokenRepository $tokenRepository, AccessTokenRequest $accessTokenRequest)
	{
		$this->tokenRepository = $tokenRepository;
		$this->accessTokenRequest = $accessTokenRequest;
	}

	/**
	 * Get an access token from the repository,
	 * or request a new access token
	 *
	 * @return \tbclla\Revolut\Auth\AccessToken
	 */
	public function getAccessToken()
	{
		$accessToken = $this->tokenRepository->getAccessToken();

		return $accessToken ?? $this->refreshAccessToken();
	}

	/**
	 * Get a refresh token from the repository
	 *
	 * @return \tbclla\Revolut\Auth\RefreshToken|null
	 */
	public function getRefreshToken()
	{
		return $this->tokenRepository->getRefreshToken();
	}

	/**
	 * Get the latest state token from the repository
	 *
	 * @return \tbclla\Revolut\Auth\State|null
	 */
	public function getState()
	{
		return $this->tokenRepository->getState();
	}

	/**
	 * Validate a string against the most recent state
	 *
	 * @param string $received The received state value
	 * @return bool
	 */
	public function validateState(string $received)
	{
		if ($actual = $this->getState()) {
			if ($actual->value === $received) {
				$this->tokenRepository->deleteState($actual);
				return true;
			}
		}

		return false;
	}

	/**
	 * Exchange a refresh token for a new access token
	 *
	 * @return \tbclla\Revolut\Auth\AccessToken
	 * @throws \tbclla\Revolut\Exceptions\RevolutException
	 */
	public function refreshAccessToken()
	{
		if (!$refreshToken = $this->getRefreshToken()) {
			throw new RevolutException('No refresh token found. Re-authorization required.');
		}

		return $this->requestAccessToken($refreshToken);
	}

	/**
	 * Request a new access token
	 *
	 * @param \tbclla\Revolut\Interfaces\GrantsAccessTokens $token
	 * @return \tbclla\Revolut\Auth\AccessToken
	 */
	public function requestAccessToken(GrantsAccessTokens $token)
	{
		$response = $this->accessTokenRequest->exchange($token);

		if (isset($response['refresh_token'])) {
			$this->tokenRepository->createRefreshToken($response['refresh_token']);
		}

		return $this->tokenRepository->createAccessToken($response['access_token']);
	}
}
