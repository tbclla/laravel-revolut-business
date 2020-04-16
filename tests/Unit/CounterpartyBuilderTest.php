<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Builders\CounterpartyBuilder;
use tbclla\Revolut\Resources\Counterparty;

class CounterpartyBuilderTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->counterparty = resolve(Counterparty::class);
		$this->builder = new CounterpartyBuilder($this->counterparty);
	}

	/** @test */
	public function a_counterparty_can_be_built()
	{
		$builder = $this->counterparty->build();

		$this->assertInstanceOf(CounterpartyBuilder::class, $builder);
	}

	/** @test */
	public function a_counterparty_builder_can_build_a_personal_revolut_counterparty()
	{
		$this->builder->personal('John Doe', '+1234567890');

		$this->assertEquals([
			'profile_type' => 'personal',
			'name' => 'John Doe',
			'phone' => '+1234567890'
		], $this->builder->toArray());
	}

	/** @test */
	public function a_counterparty_builder_can_build_a_business_revolut_counterparty()
	{
		$this->builder->business('test@sandboxcorp.com');

		$this->assertEquals([
			'profile_type' => 'business',
			'email' => 'test@sandboxcorp.com',
		], $this->builder->toArray());
	}

	/** @test */
	public function a_counterparty_builder_can_build_an_external_counterparty()
	{
		$this->builder
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
			->phone('+447771234455');
			
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
		], $this->builder->toArray());
	}

	/** @test */
	public function an_external_counterparties_address_can_be_set_with_an_array()
	{
		$address = [
			'street_line_1' => '1 Canada Square',
			'street_line_2' => 'Canary Wharf',
			'region' => 'East End',
			'postcode' => 'E115AB',
			'city' => 'London',
			'country' => 'GB'
		];

		$this->builder->address($address);

		$this->assertEquals([
			'address' => $address
		], $this->builder->toArray());
	}

	/** @test */
	public function an_external_counterparties_address_can_be_built_fluently()
	{
		$this->builder->streetLine1('1 Canada Square')
			->streetLine2('Canary Wharf')
			->region('East End')
			->postcode('E115AB')
			->city('London')
			->country('GB');

		$this->assertEquals([
			'address' => [
				'street_line_1' => '1 Canada Square',
				'street_line_2' => 'Canary Wharf',
				'region' => 'East End',
				'postcode' => 'E115AB',
				'city' => 'London',
				'country' => 'GB'
			]
		], $this->builder->toArray());
	}
}
