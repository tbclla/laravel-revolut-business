<?php

namespace tbclla\Revolut\Tests;

use Orchestra\Testbench\TestCase;
use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Client;
use tbclla\Revolut\GuzzleHttpClient;
use tbclla\Revolut\Resources\Account;

class AccountTest extends TestCase
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
	public function get_all_accounts()
	{
		$expectedUrl = $this->revolutClient->buildUri(Account::ENDPOINT);

		$this->mockHttpClient->shouldReceive('get')->withArgs([
			$expectedUrl,
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				]
			]
		]);
		
		$this->revolutClient->account()->all();
	}

	/** @test */
	public function get_an_account()
	{
		$id = 5;
		$expectedUrl = $this->revolutClient->buildUri(Account::ENDPOINT) . '/' . $id;

		$this->mockHttpClient->shouldReceive('get')->withArgs([
			$expectedUrl,
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				]
			]
		]);
		
		$this->revolutClient->account()->get($id);
	}

	/** @test */
	public function get_account_details()
	{
		$id = 5;
		$expectedUrl = $this->revolutClient->buildUri(Account::ENDPOINT) . '/' . $id . '/bank-details';

		$this->mockHttpClient->shouldReceive('get')->withArgs([
			$expectedUrl,
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				]
			]
		]);
		
		$this->revolutClient->account()->details($id);
	}
}
