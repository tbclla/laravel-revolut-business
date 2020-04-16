<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\AuthorizationCode;
use tbclla\Revolut\Auth\ClientAssertion;
use tbclla\Revolut\Auth\RefreshToken;
use tbclla\Revolut\Auth\Requests\AccessTokenRequest;
use tbclla\Revolut\Auth\State;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\GuzzleHttpClient;
use tbclla\Revolut\Repositories\TokenRepository;

class TokenManagerTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp() : void
	{
		parent::setUp();

		$this->artisan('migrate')->run();

		$this->mockHttpClient = $this->mock(GuzzleHttpClient::class);
	}

	/** @test */
	public function a_token_manager_can_get_an_existing_refresh_token()
	{
		$tokenValue = '34hjwiufhw93fh3f';

		RefreshToken::create(['value' => $tokenValue]);

		$manager = resolve(TokenManager::class);

		$this->assertEquals($tokenValue, $manager->getRefreshToken()->value);
	}

	/** @test */
	public function a_token_manager_can_get_an_existing_access_token()
	{
		$tokenValue = '384fh9jw8hjw9e8fjowf';

		AccessToken::create(['value' => $tokenValue]);

		$manager = resolve(TokenManager::class);

		$this->assertEquals($tokenValue, $manager->getAccessToken()->value);
	}

	/** @test */
	public function a_token_manager_can_exchange_a_refresh_token_for_an_access_token()
	{
		RefreshToken::create(['value' => 'je8348f398j3984fj39']);

		$this->mockHttpClient->shouldReceive('post')
							 ->andReturn(['access_token' => 'askdfjalskdfjas']);

		$tokenRepo = new TokenRepository;
		$accessTokenRequest = new AccessTokenRequest(resolve(ClientAssertion::class), $this->mockHttpClient);
		$tokenManager = new TokenManager($tokenRepo, $accessTokenRequest);

		$token = $tokenManager->refreshAccessToken();

		$this->assertEquals('access_token', $token->type);
	}

	/** @test */
	public function a_token_manager_can_exchange_an_authorization_code_for_an_access_token()
	{
		$this->mockHttpClient->shouldReceive('post')->andReturn([
			'access_token' => 'a_sample_access_token',
			'refresh_token' => 'a_sample_refresh_token'
		]);

		$tokenRepo = new TokenRepository;
		$accessTokenRequest = new AccessTokenRequest(resolve(ClientAssertion::class), $this->mockHttpClient);
		$tokenManager = new TokenManager($tokenRepo, $accessTokenRequest);
		$authorizationCode = new AuthorizationCode('a_sample_auth_code');
		
		$tokenManager->requestAccessToken($authorizationCode);
		$accessToken = $tokenManager->getAccessToken();
		$refreshToken = $tokenManager->getRefreshToken();

		$this->assertEquals('a_sample_access_token', $accessToken->value);
		$this->assertEquals('a_sample_refresh_token', $refreshToken->value);
	}
}
