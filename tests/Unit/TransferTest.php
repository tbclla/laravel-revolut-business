<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Client;
use tbclla\Revolut\Resources\Transfer;

class TransferTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->mockClient = $this->mock(Client::class);
		$this->transfer = new Transfer($this->mockClient);
	}

	/** @test */
	public function a_transfer_can_be_created()
	{
		$data = [
			'request_id' => 'e0cbf84637264ee082a848b',
			'source_account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c',
			'target_account_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
			'amount' => 123.11,
			'currency' => 'EUR',
			'reference' => 'Expenses funding'
		];

		$this->mockClient->shouldReceive()->post(Transfer::ENDPOINT, [
			'json' => $data
		]);
		
		$this->transfer->create($data);
	}
}
