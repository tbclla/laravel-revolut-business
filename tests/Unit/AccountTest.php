<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Client;
use tbclla\Revolut\Resources\Account;

class AccountTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp() : void
	{
		parent::setUp();

		$this->mockClient = $this->mock(Client::class);
	}

	/** @test */
	public function get_all_accounts()
	{
		$this->mockClient->shouldReceive()->get(Account::ENDPOINT);

		$account = new Account($this->mockClient);
		$account->all();
	}

	/** @test */
	public function get_an_account()
	{
		$id = 'ac57ffc9-a5cb-4322-89d2-088e8a007a97';

		$this->mockClient->shouldReceive()->get(Account::ENDPOINT . '/' . $id);

		$account = new Account($this->mockClient);
		$account->get($id);
	}

	/** @test */
	public function get_account_details()
	{
		$id = 'ac57ffc9-a5cb-4322-89d2-088e8a007a97';

		$this->mockClient->shouldReceive()->get(Account::ENDPOINT . '/' . $id . '/bank-details');

		$account = new Account($this->mockClient);
		$account->details($id);
	}
}
