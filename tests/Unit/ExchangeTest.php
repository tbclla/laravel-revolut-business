<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Client;
use tbclla\Revolut\Resources\Exchange;

class ExchangeTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->mockClient = $this->mock(Client::class);
		$this->exchange = new Exchange($this->mockClient);
	}

	/** @test */
	public function an_exchange_can_be_created()
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

		$this->mockClient->shouldReceive()->post(Exchange::ENDPOINT, [
			'json' => $data
		]);

		$this->exchange->create($data);
	}
}
