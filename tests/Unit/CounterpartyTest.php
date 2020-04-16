<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Client;
use tbclla\Revolut\Resources\Counterparty;

class CounterpartyTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->mockClient = $this->mock(Client::class);
		$this->counterparty = new Counterparty($this->mockClient);
	}

	/** @test */
	public function all_counterparties_can_be_retrieved()
	{
		$this->mockClient->shouldReceive()->get('/counterparties');

		$this->counterparty->all();
	}

	/** @test */
	public function a_counterparty_can_be_retrieved_by_id()
	{
		$id = '5435ff9e-bacd-430b-95c2-094da8662829';

		$this->mockClient->shouldReceive()->get(Counterparty::ENDPOINT . '/' . $id);

		$this->counterparty->get($id);
	}

	/** @test */
	public function a_counterparty_can_be_deleted()
	{
		$id = '5435ff9e-bacd-430b-95c2-094da8662829';

		$this->mockClient->shouldReceive()->delete(Counterparty::ENDPOINT . '/' . $id);

		$this->counterparty->delete($id);
	}

	/** @test */
	public function a_counterparty_can_be_created()
	{
		$data = [
			'profile_type' => 'personal',
			'name' => 'John Smith',
			'phone' => '+44723456789'
		];

		$this->mockClient->shouldReceive()->post(Counterparty::ENDPOINT, [
			'json' => $data
		]);
		
		$this->counterparty->create($data);
	}
}
