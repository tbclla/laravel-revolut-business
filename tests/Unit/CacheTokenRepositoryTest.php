<?php

namespace tbclla\Revolut\Tests;

use Illuminate\Support\Facades\Cache;
use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\RefreshToken;
use tbclla\Revolut\Repositories\CacheTokenRepository;

class CacheTokenRepositoryTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->repo = new CacheTokenRepository;
	}

	/** @test */
	public function a_cache_token_repository_can_create_an_access_token()
	{
		$key = CacheTokenRepository::PREFIX . AccessToken::TYPE;

		$this->repo->createAccessToken('example_value');

		$this->assertTrue(Cache::has($key));
	}

	/** @test */
	public function a_cache_token_repository_can_get_an_access_token()
	{
		$value = md5(time());

		$this->repo->createAccessToken($value);

		$accessToken = $this->repo->getAccessToken();

		$this->assertEquals($value, $accessToken->value);
	}

	/** @test */
	public function a_cache_token_repository_can_create_a_refresh_token()
	{
		$key = CacheTokenRepository::PREFIX . RefreshToken::TYPE;

		$this->repo->createRefreshToken('example_value');

		$this->assertTrue(Cache::has($key));
	}

	/** @test */
	public function a_cache_token_repository_can_get_a_refresh_token()
	{
		$value = md5(time());

		$this->repo->createRefreshToken($value);

		$refreshToken = $this->repo->getRefreshToken();

		$this->assertEquals($value, $refreshToken->value);
	}
}
