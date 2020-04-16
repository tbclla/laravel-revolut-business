<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Client;
use tbclla\Revolut\GuzzleHttpClient;
use tbclla\Revolut\Resources\Counterparty;

class CounterpartyTest extends TestCase
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

	/** @test */
	public function create_a_counterparty()
	{
		$data = [
			'profile_type' => 'personal',
			'name' => 'John Smith',
			'phone' => '+44723456789'
		];

		$this->mockHttpClient->shouldReceive('post')->withArgs([
			$this->revolutClient->buildUri(Counterparty::ENDPOINT),
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				],
				'json' => $data,
			]
		]);

		$this->revolutClient->counterparty()->create($data);
	}

	/** @test */
	public function build_personal_revolut_counterparty()
	{
		$counterparty = $this->revolutClient->counterparty()->build()->personal('John Doe', '+1234567890');

		$this->assertEquals([
			'profile_type' => 'personal',
			'name' => 'John Doe',
			'phone' => '+1234567890'
		], $counterparty->toArray());
	}

	/** @test */
	public function build_business_revolut_counterparty()
	{
		$counterparty = $this->revolutClient->counterparty()->build()->business('test@sandboxcorp.com');

		$this->assertEquals([
			'profile_type' => 'business',
			'email' => 'test@sandboxcorp.com',
		], $counterparty->toArray());
	}

	/** @test */
	public function build_external_counterparty()
	{
		$counterparty = $this->revolutClient->counterparty()->build()
			->individualName('John', 'Smith')
			->companyName('John Smith Co.')
			->bankCountry('GB')
			->currency('GBP')
			->accountNumber('12345678')
			->sortCode('223344')
			->routingNumber('123456')
			->iban('GB12123412341234')
			->bic('REVOGB12')
			->clabe('1234567890')
			->email('john@smith.co')
			->phone('+447771234455')
			->streetLine1('1 Canada Square')
			->streetLine2('Canary Wharf')
			->region('East End')
			->postcode('E115AB')
			->city('London')
			->country('GB');

		$this->assertEquals([
			'individual_name' => [
				'first_name' => 'John',
				'last_name' => 'Smith',
			],
			'company_name' => 'John Smith Co.',
			'bank_country' => 'GB',
			'currency' => 'GBP',
			'account_no' => '12345678',
			'sort_code' => '223344',
			'routing_number' => '123456',
			'iban' => 'GB12123412341234',
			'bic' => 'REVOGB12',
			'clabe' => '1234567890',
			'email' => 'john@smith.co',
			'phone' => '+447771234455',
			'address' => [
				'street_line_1' => '1 Canada Square',
				'street_line_2' => 'Canary Wharf',
				'region' => 'East End',
				'postcode' => 'E115AB',
				'city' => 'London',
				'country' => 'GB'
			]
		], $counterparty->toArray());
	}
}
