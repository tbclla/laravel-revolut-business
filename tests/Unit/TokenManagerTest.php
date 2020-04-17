<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Auth\AuthorizationCode;
use tbclla\Revolut\Auth\ClientAssertion;
use tbclla\Revolut\Auth\Requests\AccessTokenRequest;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Interfaces\MakesHttpRequests;
use tbclla\Revolut\Repositories\TokenRepository;

class managerTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp() : void
	{
		parent::setUp();

		$this->artisan('migrate')->run();

		$this->mockHttpClient = $this->mock(MakesHttpRequests::class);
	}

	/** @test */
	public function a_token_manager_can_get_an_existing_refresh_token()
	{
		$tokenValue = md5(time());

		$manager = resolve(TokenManager::class);
		$manager->createRefreshToken($tokenValue);

		$this->assertEquals($tokenValue, $manager->getRefreshToken()->value);
	}

	/** @test */
	public function a_token_manager_can_get_an_existing_access_token()
	{
		$tokenValue = md5(time());

		$manager = resolve(TokenManager::class);
		$manager->createAccessToken($tokenValue);

		$this->assertEquals($tokenValue, $manager->getAccessToken()->value);
	}

	/** @test */
	public function a_token_manager_can_exchange_a_refresh_token_for_an_access_token()
	{
		$tokenValue = md5(time());

		$accessTokenRequest = new AccessTokenRequest(resolve(ClientAssertion::class), $this->mockHttpClient);
		$manager = new TokenManager(resolve(TokenRepository::class), $accessTokenRequest);

		$manager->createRefreshToken(md5(time()));

		$this->mockHttpClient->shouldReceive('post')->andReturn(['access_token' => $tokenValue]);

		$this->assertEquals($tokenValue, $manager->refreshAccessToken()->value);
	}

	/** @test */
	public function a_token_manager_can_exchange_an_authorization_code_for_an_access_token()
	{
		$accessTokenValue = 'example_access_token';
		$refreshTokenValue = 'example_refresh_token';

		$accessTokenRequest = new AccessTokenRequest(resolve(ClientAssertion::class), $this->mockHttpClient);
		$manager = new TokenManager(resolve(TokenRepository::class), $accessTokenRequest);

		$authorizationCode = new AuthorizationCode('example_auth_code');

		$this->mockHttpClient->shouldReceive('post')->andReturn([
			'access_token' => $accessTokenValue,
			'refresh_token' => $refreshTokenValue,
		]);
		
		$manager->requestAccessToken($authorizationCode);

		$this->assertEquals($accessTokenValue, $manager->getAccessToken()->value);
		$this->assertEquals($refreshTokenValue, $manager->getRefreshToken()->value);
	}
}
