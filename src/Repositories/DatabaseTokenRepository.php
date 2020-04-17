<?php

namespace tbclla\Revolut\Repositories;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\RefreshToken;
use tbclla\Revolut\Interfaces\TokenRepository;

class DatabaseTokenRepository implements TokenRepository
{
	public function getAccessToken()
	{
		return AccessToken::active()->orderBy('id', 'desc')->first();
	}

	public function getRefreshToken()
	{
		return RefreshToken::orderBy('id', 'desc')->first();
	}

	public function createAccessToken(string $value)
	{
		return AccessToken::create([
			'value' => $value
		]);
	}

	public function createRefreshToken(string $value)
	{
		return RefreshToken::create([
			'value' => $value
		]);
	}
}
