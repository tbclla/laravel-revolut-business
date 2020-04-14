<?php

namespace tbclla\Revolut\Auth;

use tbclla\Revolut\Auth\Token;
use tbclla\Revolut\Interfaces\GrantsAccessTokens;
use tbclla\Revolut\Interfaces\PersistableToken;

class RefreshToken extends Token implements GrantsAccessTokens, PersistableToken
{
	/**
	 * The type of the token
	 * 
	 * @var string
	 */
	const TYPE = 'refresh_token';

	/**
	 * The grant type of the token
	 * 
	 * @var string
	 */
	const GRANT_TYPE = 'refresh_token';

	/**
	 * Get the token value
	 *
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Get the type
	 *
	 * @return string
	 */
	public static function getType()
	{
		return self::TYPE;
	}

	/**
	 * Get the expiration date
	 *
	 * @return null
	 */
	public static function getExpiration()
	{
		return null;
	}

	/**
	 * Get the grant type of the token
	 * 
	 * @return string
	 */
	public static function getGrantType()
	{
		return self::GRANT_TYPE;
	}

	/**
	 * Delete all expired refresh tokens
	 * 
	 * @return int The number of deleted tokens
	 */
	public static function clearExpired()
	{
		$latest = self::latest()->select('id')->first();

		return (int) self::where('id', '!=', $latest->id)->delete();
	}
}
