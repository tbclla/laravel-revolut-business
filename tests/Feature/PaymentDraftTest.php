<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Client;
use tbclla\Revolut\GuzzleHttpClient;
use tbclla\Revolut\Resources\PaymentDraft;
use Orchestra\Testbench\TestCase;

class PaymentDraftTest extends TestCase
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
		$app['config']->set('revolut.auth_route.name', 'revolut-authorization');
		$app['config']->set('revolut.auth_route.middleware', []);
	}

	/** @test */
	public function a_payment_draft__can_be_created()
	{
		$data = [
			'title' => 'Title of payment',
			'schedule_for' => '2017-10-10',
			'payments' => [
				[
					'currency' => 'EUR',
					'amount' => 123,
					'account_id' => 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab',
					'receiver' => [
						'counterparty_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
						'account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c'
					],
					'reference' => 'External transfer'
				]
			]
		];

		$this->mockHttpClient->shouldReceive('post')->withArgs([
			$this->revolutClient->buildUri(PaymentDraft::ENDPOINT),
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				],
				'json' => $data,
			]
		]);

		$this->revolutClient->paymentDraft()->create($data);
	}

	/** @test */
	public function a_payment_draft_can_be_built()
	{
		$draft = $this->revolutClient->paymentDraft()->build()
			->title('Title of payment')
			->schedule('2017-10-10')
			->payments([
				'currency' => 'EUR',
				'amount' => 123,
				'account_id' => 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab',
				'receiver' => [
					'counterparty_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
					'account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c'
				],
				'reference' => 'External transfer'
			]);

		$this->assertEquals([
			'title' => 'Title of payment',
			'schedule_for' => '2017-10-10',
			'payments' => [
				'currency' => 'EUR',
				'amount' => 123,
				'account_id' => 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab',
				'receiver' => [
					'counterparty_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
					'account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c'
				],
				'reference' => 'External transfer'
			],
		], $draft->toArray());
	}
}
