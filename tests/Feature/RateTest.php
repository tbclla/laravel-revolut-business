<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Client;
use tbclla\Revolut\GuzzleHttpClient;
use tbclla\Revolut\Resources\Rate;

class RateTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp() : void
	{
		parent::setUp();

		$this->artisan('migrate')->run();

		// Create a mock Http Client
		$this->mockHttpClient = $this->mock(GuzzleHttpClient::class);

		// make an access token
		$this->accessToken = new AccessToken(['value' => 'sample_access_token']);

		// instantiante a Revolut client with the mock Http client
		$this->revolutClient = new Client(resolve(TokenManager::class), $this->mockHttpClient);
		// assign it the access token
		$this->revolutClient->setAccessToken($this->accessToken);
	}

	/** @test */
	public function get_rate_without_amount()
	{
		$this->mockHttpClient->shouldReceive('get')->withArgs([
			$this->revolutClient->buildUri(Rate::ENDPOINT),
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				],
				'query' => [
					'from' => 'USD',
					'to' => 'EUR',
					'amount' => 1
				]
			]
		]);
		
		$this->revolutClient->rate()->get('USD', 'EUR');
	}

	/** @test */
	public function get_rate_with_amount()
	{
		$this->mockHttpClient->shouldReceive('get')->withArgs([
			$this->revolutClient->buildUri(Rate::ENDPOINT),
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				],
				'query' => [
					'from' => 'USD',
					'to' => 'EUR',
					'amount' => 55.34
				]
			]
		]);

		$this->revolutClient->rate()->get('USD', 'EUR', 55.34);
	}
}
