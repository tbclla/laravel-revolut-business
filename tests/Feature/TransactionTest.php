<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Client;
use tbclla\Revolut\GuzzleHttpClient;
use tbclla\Revolut\Resources\Transaction;
use Orchestra\Testbench\TestCase;

class TransactionTest extends TestCase
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
	public function all_transactions_can_be_retrieved()
	{
		$expectedUrl = $this->revolutClient->buildUri(Transaction::ENDPOINT);

		$this->mockHttpClient->shouldReceive('get')->withArgs([
			$expectedUrl,
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				],
				'query' => [

				]
			]
		]);

		$this->revolutClient->transaction()->all();
	}

	/** @test */
	public function a_transaction_can_be_retrieved_by_id()
	{
		$id = '62b61a4f-fb09-4e87-b0ab-b66c85f5485c';
		$expectedUrl = $this->revolutClient->buildUri('/transaction/' . $id);

		$this->mockHttpClient->shouldReceive('get')->withArgs([
			$expectedUrl,
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				]
			]
		]);

		$this->revolutClient->transaction()->get($id);
	}

	/** @test */
	public function a_transaction_can_be_retrieved_by_request_id()
	{
		$requestId = 'e0cbf84637264ee082a848b';
		$expectedUrl = $this->revolutClient->buildUri('/transaction/' . $requestId);

		$this->mockHttpClient->shouldReceive('get')->withArgs([
			$expectedUrl,
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				],
				'query' => [
					'id_type' => 'request_id'
				]
			]
		]);

		$this->revolutClient->transaction()->getByRequestId($requestId);
	}
}
