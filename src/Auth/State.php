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

	public static function getType()
	{
		return self::TYPE;
	}

	public static function getExpiration()
	{
		return now()->addMinutes(self::TTL);
	}
}
