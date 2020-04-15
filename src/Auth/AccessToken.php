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

	public static function getType()
	{
		return self::TYPE;
	}

	public static function getExpiration()
	{
		return now()->addMinutes(self::TTL);
	}
}
