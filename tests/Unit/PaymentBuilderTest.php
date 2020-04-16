<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Resources\Payment;
use tbclla\Revolut\Builders\PaymentBuilder;

class PaymentBuilderTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->payment = resolve(Payment::class);
		$this->builder = new PaymentBuilder($this->payment);
	}

	/** @test */
	public function a_payment_can_be_built()
	{
		$this->builder
			->account('bdab1c20-8d8c-430d-b967-87ac01af060c')
			->receiver('5138z40d1-05bb-49c0-b130-75e8cf2f7693', 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab')
			->amount(123.11)
			->currency('EUR')
			->reference('Invoice payment #123')
			->requestId('d55df8dc-fecc-429c-b000-3ccbd990d0b3');

		$this->assertEquals([
			'account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c',
			'receiver' => [
				'counterparty_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
				'account_id' => 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab'
			],
			'amount' => 123.11,
			'currency' => 'EUR',
			'reference' => 'Invoice payment #123',
			'request_id' => 'd55df8dc-fecc-429c-b000-3ccbd990d0b3',
		], $this->builder->toArray());
	}

	/** @test */
	public function calling_the_builder_from_the_payment_sets_a_request_id()
	{
		$builder = $this->payment->build();

		$data = $builder->toArray();

		$this->assertNotEmpty($data['request_id']);
	}
}
