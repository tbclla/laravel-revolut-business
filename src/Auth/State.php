<?php

namespace tbclla\Revolut\Auth;

use tbclla\Revolut\Auth\Token;
use tbclla\Revolut\Interfaces\PersistableToken;

class State extends Token implements PersistableToken
{
	/**
	 * The type of the token
	 * 
	 * @var string
	 */
	const TYPE = 'auth_state';

	/**
	 * The time to live in minutes
	 * 
	 * @var int
	 */
	const TTL = 5;

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
