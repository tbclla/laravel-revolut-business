<?php

namespace tbclla\Revolut\Interfaces;

interface PersistableToken
{
	public static function getType();

	public static function getExpiration();
}
