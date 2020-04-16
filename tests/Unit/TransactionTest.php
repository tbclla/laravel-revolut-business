<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Client;
use tbclla\Revolut\Resources\Transaction;

class TransactionTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->mockClient = $this->mock(Client::class);
		$this->transaction = new Transaction($this->mockClient);
	}

	/** @test */
	public function all_transactions_can_be_retrieved_without_arguments()
	{
		$this->mockClient->shouldReceive()->get(Transaction::ENDPOINT, [
			'query' => []
		]);

		$this->transaction->all();
	}

	/** @test */
	public function all_transactions_can_be_retrieved_with_filters()
	{
		$filters = [
			'count' => 200,
			'type' => 'card_payment',
		];

		$this->mockClient->shouldReceive()->get(Transaction::ENDPOINT, [
			'query' => $filters
		]);

		$this->transaction->all($filters);
	}

	/** @test */
	public function the_client_can_be_forced_to_refresh_its_access_token_before_retrieving_transactions()
	{
		$this->mockClient->shouldReceive()->refreshAccessToken()->once();
		$this->mockClient->shouldReceive()->get(Transaction::ENDPOINT, [
			'query' => []
		]);;

		$this->transaction->all([], true);
	}

	/** @test */
	public function a_transaction_can_be_retrieved_by_id()
	{
		$id = '62b61a4f-fb09-4e87-b0ab-b66c85f5485c';

		$this->mockClient->shouldReceive()->get('/transaction/' . $id);

		$this->transaction->get($id);
	}

	/** @test */
	public function a_transaction_can_be_retrieved_by_request_id()
	{
		$requestId = 'e0cbf84637264ee082a848b';

		$this->mockClient->shouldReceive()->get('/transaction/' . $requestId, [
			'query' => [
				'id_type' => 'request_id'
			]
		]);

		$this->transaction->getByRequestId($requestId);
	}
}
