<?php

namespace tbclla\Revolut\Resources;

use tbclla\Revolut\Builders\TransferBuilder;
use tbclla\Revolut\Interfaces\Buildable;

class Transfer extends Resource implements Buildable
{
	/**
	 * The enpoint for transfer requests
	 * 
	 * @var string
	 */
	const ENDPOINT = '/transfer';

	/**
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#transfers-create-transfer Official API documentation
	 */
	public function create(array $json)
	{
		return $this->client->post(self::ENDPOINT, ['json' => $json]);
	}

	/**
	 * @return \tbclla\Revolut\Builders\TransferBuilder
	 */
	public function build()
	{
		return new TransferBuilder($this, $this->client->generateRequestId());
	}
}
