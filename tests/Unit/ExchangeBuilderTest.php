<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Builders\ExchangeBuilder;
use tbclla\Revolut\Resources\Exchange;

class ExchangeBuilderTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->exchange = resolve(Exchange::class);
		$this->builder = new ExchangeBuilder($this->exchange);
	}

	/** @test */
	public function a_currency_sale_can_be_built()
	{
		$this->builder
			->from('d56dd396-523b-4613-8cc7-54974c17bcac', 'EUR', 100)
			->to('a44dd365-523b-4613-8457-54974c8cc7ac', 'USD')
			->reference('Time to sell')
			->requestId('d55df8dc-fecc-429c-b000-3ccbd990d0b3');

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
			'request_id' => 'd55df8dc-fecc-429c-b000-3ccbd990d0b3',

		], $this->builder->toArray());
	}

	/** @test */
	public function a_currency_purchase_can_be_built()
	{
		$this->builder
			->from('source_account_id', 'EUR')
			->to('target_account_id', 'USD', 100)
			->reference('Time to buy')
			->requestId('d55df8dc-fecc-429c-b000-3ccbd990d0b3');

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
			'request_id' => 'd55df8dc-fecc-429c-b000-3ccbd990d0b3',

		], $this->builder->toArray());
	}

	/** @test */
	public function calling_the_builder_from_the_exchange_sets_a_request_id()
	{
		$builder = $this->exchange->build();

		$data = $builder->toArray();

		$this->assertNotEmpty($data['request_id']);
	}
}
