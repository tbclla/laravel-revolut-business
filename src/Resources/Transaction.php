<?php

namespace tbclla\Revolut\Resources;

class Transaction extends Resource
{
	/**
	 * The enpoint for transaction requests
	 * 
	 * @var string
	 */
	const ENDPOINT = '/transactions';

	/**
	 * Get up to 1000 historical transactions based on the provided query criteria
	 * 
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#payments-get-transactions Official API documentation
	 * @param array $filters accepts optional 'from', 'to', 'counterparty', 'count', type'
	 * @param bool $useFreshToken Force the use of a new access token.
	 * @return array
	 */
	public function all(array $filters = [], $useFreshToken = false)
	{
		if ($useFreshToken) {
			$this->client->refreshAccessToken();
		}

		return $this->client->get(self::ENDPOINT, ['query' => $filters]);
	}

	/**
	 * Get a transaction by its ID
	 * 
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#payments-get-transaction Official API documentation
	 * @param string $id The transaction ID in UUID format
	 * @return array
	 */
	public function get(string $id)
	{
		return $this->client->get('/transaction/' . $id);
	}

	/**
	 * Get a transaction by its request ID
	 * 
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#payments-get-transaction Official API documentation
	 * @param string $requestId The request ID
	 * @return array
	 */
	public function getByRequestId(string $requestId)
	{
		return $this->client->get('/transaction/' . $requestId, [
			'query' => [
				'id_type' => 'request_id'
			]
		]);
	}
}
