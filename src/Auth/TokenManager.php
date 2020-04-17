<?php

namespace tbclla\Revolut\Auth;

use tbclla\Revolut\Auth\Requests\AccessTokenRequest;
use tbclla\Revolut\Exceptions\AppUnauthorizedException;
use tbclla\Revolut\Interfaces\GrantsAccessTokens;
use tbclla\Revolut\Interfaces\TokenRepository;

class TokenManager
{
	/**
	 * The token repository
	 *
	 * @var \tbclla\Revolut\Interfaces\TokenRepository
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
	 * @param \tbclla\Revolut\Interfaces\TokenRepository $tokenRepository
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
	 * Store a new access token
	 *
	 * @param string $value
	 * @return \tbclla\Revolut\Auth\AccessToken
	 */
	public function createAccessToken(string $value)
	{
		return $this->tokenRepository->createAccessToken($value);
	}

	/**
	 * Store a new refresh token
	 *
	 * @param string $value
	 * @return \tbclla\Revolut\Auth\RefreshToken
	 */
	public function createRefreshToken(string $value)
	{
		return $this->tokenRepository->createRefreshToken($value);
	}

	/**
	 * Exchange a refresh token for a new access token
	 *
	 * @return \tbclla\Revolut\Auth\AccessToken
	 * @throws \tbclla\Revolut\Exceptions\AppUnauthorizedException
	 */
	public function refreshAccessToken()
	{
		if (!$refreshToken = $this->getRefreshToken()) {
			throw new AppUnauthorizedException('No refresh token found. Re-authorization required.');
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
			$this->createRefreshToken($response['refresh_token']);
		}

		return $this->createAccessToken($response['access_token']);
	}
}
