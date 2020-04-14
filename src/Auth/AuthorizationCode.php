<?php

namespace tbclla\Revolut\Auth;

use tbclla\Revolut\Interfaces\GrantsAccessTokens;

class AuthorizationCode implements GrantsAccessTokens
{
	/**
	 * The token value
	 * 
	 * @var string
	 */
	public $value;

	/**
	 * Create a new authorization code instance
	 * 
	 * @param string $value The authorization code supplied by Revolut
	 */
	public function __construct(string $value)
	{
		$this->value = $value;
	}

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
		return 'code';
	}

	/**
	 * Get the grant type of the token
	 * 
	 * @var string
	 */
	public static function getGrantType()
	{
		return 'authorization_code';
	}
}
