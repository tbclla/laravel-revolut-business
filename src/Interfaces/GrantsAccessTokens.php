<?php

namespace tbclla\Revolut\Interfaces;

interface GrantsAccessTokens
{
	public function getValue();
	
	public static function getType();

	public static function getGrantType();
}
