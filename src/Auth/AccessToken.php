<?php

namespace tbclla\Revolut\Auth;

use tbclla\Revolut\Auth\Token;
use tbclla\Revolut\Interfaces\PersistableToken;

class AccessToken extends Token implements PersistableToken
{
	/**
	 * The name of the token
	 * 
	 * @var string
	 */
	const TYPE = 'access_token';

	/**
	 * The time to live in minutes
	 * 
	 * @var int
	 */
	const TTL = 40;

	/**
	 * Get the type of the token
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
	 * @return \Carbon\Carbon
	 */
	public static function getExpiration()
	{
		return now()->addMinutes(self::TTL);
	}
}
