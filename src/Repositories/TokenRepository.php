<?php

namespace tbclla\Revolut\Repositories;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\RefreshToken;
use tbclla\Revolut\Auth\State;

class TokenRepository
{
	/**
	 * Get the latest active access token
	 *
	 * @return \tbclla\Revolut\Auth\AccessToken|null
	 */
	public function getAccessToken()
	{
		return AccessToken::active()->orderBy('id', 'desc')->first();
	}

	/**
	 * Get the latest refresh token
	 *
	 * @return \tbclla\Revolut\Auth\RefreshToken|null
	 */
	public function getRefreshToken()
	{
		return RefreshToken::orderBy('id', 'desc')->first();
	}

	/**
	 * Get the latest State
	 *
	 * @return null|\tbclla\Revolut\Auth\State
	 */
	public function getState()
	{
		return State::active()->orderBy('id', 'desc')->first();
	}

	/**
	 * Create a new access token
	 *
	 * @param string $value
	 * @return \tbclla\Revolut\Auth\AccessToken
	 */
	public function createAccessToken(string $value)
	{
		return AccessToken::create([
			'value' => $value
		]);
	}

	/**
	 * Create a new refresh token
	 *
	 * @param string $value
	 * @return \tbclla\Revolut\Auth\RefreshToken
	 */
	public function createRefreshToken(string $value)
	{
		return RefreshToken::create([
			'value' => $value
		]);
	}

	/**
	 * Create a new Oauth state token
	 *
	 * @param string $value
	 * @return \tbclla\Revolut\Auth\State
	 */
	public function createState(string $value)
	{
		return State::create([
			'value' => $value
		]);
	}

	/**
	 * Delete a state
	 *
	 * @param \tbclla\Revolut\Auth\State $state
	 * @return void
	 */
	public function deleteState(State $state)
	{
		$state->delete();
	}
}
