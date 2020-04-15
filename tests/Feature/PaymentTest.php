<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Client;
use tbclla\Revolut\GuzzleHttpClient;
use tbclla\Revolut\Resources\Payment;
use Orchestra\Testbench\TestCase;

class PaymentTest extends TestCase
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
		$app['config']->set('revolut.auth_route.name', 'revolut-authorization');
		$app['config']->set('revolut.auth_route.middleware', []);
	}

	protected function getPackageProviders($app)
	{
		return ['tbclla\Revolut\Providers\RevolutServiceProvider'];
	}

	/** @test */
	public function a_payment_can_be_created()
	{
		$data = [
			'request_id' => 'e0cbf84637264ee082a848b',
			'account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c',
			'receiver' => [
				'counterparty_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
				'account_id' => 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab'
			],
			'amount' => 123.11,
			'currency' => 'EUR',
			'reference' => 'Invoice payment #123'
		];

		$this->mockHttpClient->shouldReceive('post')->withArgs([
			$this->revolutClient->buildUri(Payment::ENDPOINT),
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				],
				'json' => $data,
			]
		]);

		$this->revolutClient->payment()->create($data);
	}

	/** @test */
	public function a_payment_can_be_scheduled()
	{
		$date = now()->addDays(7)->format('Y-m-d');

		$data = [
			'request_id' => 'e0cbf84637264ee082a848b',
			'account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c',
			'receiver' => [
				'counterparty_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
				'account_id' => 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab'
			],
			'amount' => 123.11,
			'currency' => 'EUR',
			'reference' => 'Invoice payment #123'
		];

		$this->mockHttpClient->shouldReceive('post')->withArgs([
			$this->revolutClient->buildUri(Payment::ENDPOINT),
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				],
				'json' => array_merge($data, ['schedule_for' => $date]),
			]
		]);

		$this->revolutClient->payment()->schedule($data, $date);
	}

	/** @test */
	public function a_payment_can_be_built()
	{
		$payment = $this->revolutClient->payment()->build()
			->account('bdab1c20-8d8c-430d-b967-87ac01af060c')
			->receiver('5138z40d1-05bb-49c0-b130-75e8cf2f7693', 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab')
			->amount(123.11)
			->currency('EUR')
			->reference('Invoice payment #123');

		$this->assertEquals([
			'account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c',
			'receiver' => [
				'counterparty_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
				'account_id' => 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab'
			],
			'amount' => 123.11,
			'currency' => 'EUR',
			'reference' => 'Invoice payment #123',
			'request_id' => $payment->request_id,
		], $payment->toArray());
	}

	/** @test */
	public function a_scheduled_payment_can_be_cancelled()
	{
		$id = '62b61a4f-fb09-4e87-b0ab-b66c85f5485c';
		$expectedUrl = $this->revolutClient->buildUri('/transaction/' . $id);

		$this->mockHttpClient->shouldReceive('delete')->withArgs([
			$expectedUrl,
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				]
			]
		]);

		$this->revolutClient->payment()->cancel($id);
	}
}
