<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Client;
use tbclla\Revolut\GuzzleHttpClient;
use tbclla\Revolut\Resources\Exchange;
use Orchestra\Testbench\TestCase;

class ExchangeTest extends TestCase
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
		// instantiante a Revolut client with the mock Http client
		$this->revolutClient = new Client(resolve(TokenManager::class), $this->mockHttpClient);
		// make sure we have an access token available
		$this->accessToken = AccessToken::create(['value' => 'sample_access_token']);
	}

	protected function getPackageProviders($app)
	{
		return ['tbclla\Revolut\Providers\RevolutServiceProvider'];
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

	/** @test */
	public function create_an_exchange()
	{
		$data = [
			'from' => [
				'account_id' => 'd56dd396-523b-4613-8cc7-54974c17bcac',
				'currency' => 'USD',
				'amount' => 135.5
			],
			'to' => [
				'account_id' => 'a44dd365-523b-4613-8457-54974c8cc7ac',
				'currency' => 'EUR'
			],
			'reference' => 'Time to sell',
			'request_id' => 'e0cbf84637264ee082a848b'
		];

		$this->mockHttpClient->shouldReceive('post')->withArgs([
			$this->revolutClient->buildUri(Exchange::ENDPOINT),
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				],
				'json' => $data,
			]
		]);

		$this->revolutClient->exchange()->create($data);
	}

	/** @test */
	public function a_currency_sale_can_be_built()
	{
		$exchange = $this->revolutClient->exchange()->build()
			->from('d56dd396-523b-4613-8cc7-54974c17bcac', 'EUR', 100)
			->to('a44dd365-523b-4613-8457-54974c8cc7ac', 'USD')
			->reference('Time to sell');

		$this->assertEquals([
			'from' => [
				'account_id' => 'd56dd396-523b-4613-8cc7-54974c17bcac',
				'currency' => 'EUR',
				'amount' => 100,
			],
			'to' => [
				'account_id' => 'a44dd365-523b-4613-8457-54974c8cc7ac',
				'currency' => 'USD',
			],
			'reference' => 'Time to sell',
			'request_id' => $exchange->request_id,
			
		], $exchange->toArray());
	}

	/** @test */
	public function a_currency_purchase_can_be_built()
	{
		$exchange = $this->revolutClient->exchange()->build()
			->from('source_account_id', 'EUR')
			->to('target_account_id', 'USD', 100)
			->reference('Time to buy');

		$this->assertEquals([
			'from' => [
				'account_id' => 'source_account_id',
				'currency' => 'EUR',
			],
			'to' => [
				'account_id' => 'target_account_id',
				'currency' => 'USD',
				'amount' => 100,
			],
			'reference' => 'Time to buy',
			'request_id' => $exchange->request_id,

		], $exchange->toArray());
	}
}
