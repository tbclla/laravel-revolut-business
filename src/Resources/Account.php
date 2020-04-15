<?php

namespace tbclla\Revolut\Resources;

class Account extends Resource
{
	/**
	 * The enpoint for account requests
	 *
	 * @var string
	 */
	const ENDPOINT = '/accounts';

	/**
	 * Get all accounts
	 * 
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#accounts-get-accounts Official API documentation
	 * @return array
	 * @throws \tbclla\Revolut\Exceptions\ApiException
	 */
	public function all()
	{
		return $this->client->get(self::ENDPOINT);
	}

	/**
	 * Get an account by its ID
	 * @throws \tbclla\Revolut\Exceptions\ApiException
	 * 
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#accounts-get-account Official API documentation
	 * @param string $id The account ID in UUID format
	 * @return array
	 */
	public function get(string $id)
	{
		return $this->client->get(self::ENDPOINT . '/' . $id);
	}

	/**
	 * Get the details of an account by its ID
	 *
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#accounts-get-account-details Official API documentation
	 * @param string $id The account ID in UUID format
	 * @return array
	 */
	public function details(string $id)
	{
		return $this->client->get(self::ENDPOINT . '/' . $id . '/bank-details');
	}
}
