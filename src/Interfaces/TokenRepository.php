<?php

namespace tbclla\Revolut\Interfaces;

interface TokenRepository
{
	/**
	 * Get the latest active access token
	 *
	 * @return \tbclla\Revolut\Auth\AccessToken|null
	 */
	public function getAccessToken();

	/**
	 * Get the latest refresh token
	 *
	 * @return \tbclla\Revolut\Auth\RefreshToken|null
	 */
	public function getRefreshToken();

	/**
	 * Create a new access token
	 *
	 * @param string $value
	 * @return \tbclla\Revolut\Auth\AccessToken
	 */
	public function createAccessToken(string $value);

	/**
	 * Create a new refresh token
	 *
	 * @param string $value
	 * @return \tbclla\Revolut\Auth\RefreshToken
	 */
	public function createRefreshToken(string $value);
}
