<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Repositories\DatabaseTokenRepository;

class DatabaseTokenRepositoryTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->artisan('migrate');

		$this->repo = new DatabaseTokenRepository;
		$this->table = config('revolut.tokens_table');
	}

	/** @test */
	public function a_db_token_repository_can_create_an_access_token()
	{
		$token = $this->repo->createAccessToken('example_value');

		$this->assertDatabaseHas($this->table, ['id' => $token->id]);
	}

	/** @test */
	public function a_db_token_repository_can_get_an_access_token()
	{
		$value = md5(time());

		$this->repo->createAccessToken($value);

		$accessToken = $this->repo->getAccessToken();

		$this->assertEquals($value, $accessToken->value);
	}

	/** @test */
	public function a_db_token_repository_can_create_a_refresh_token()
	{
		$token = $this->repo->createRefreshToken('example_value');

		$this->assertDatabaseHas($this->table, ['id' => $token->id]);
	}

	/** @test */
	public function a_db_token_repository_can_get_a_refresh_token()
	{
		$value = md5(time());

		$this->repo->createRefreshToken($value);

		$refreshToken = $this->repo->getRefreshToken();

		$this->assertEquals($value, $refreshToken->value);
	}
}
