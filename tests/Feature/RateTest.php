<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Client;
use tbclla\Revolut\GuzzleHttpClient;
use tbclla\Revolut\Resources\Rate;
use Orchestra\Testbench\TestCase;

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

	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{
		$app['config']->set('revolut.sandbox', true);
		$app['config']->set('revolut.encrypt_tokens', true);
		$app['config']->set('revolut.tokens_table', 'revolut_tokens');
		$app['config']->set('revolut.client_id', env('REVOLUT_CLIENT_ID'));
		$app['config']->set('revolut.private_key', env('REVOLUT_PRIVATE_KEY'));
		$app['config']->set('revolut.redirect_uri', env('REVOLUT_REDIRECT_URI'));
	}

	protected function getPackageProviders($app)
	{
		return ['tbclla\Revolut\Providers\RevolutServiceProvider'];
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
