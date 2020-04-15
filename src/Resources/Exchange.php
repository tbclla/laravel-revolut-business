<?php

namespace tbclla\Revolut\Resources;

use tbclla\Revolut\Builders\ExchangeBuilder;
use tbclla\Revolut\Interfaces\Buildable;

class Exchange extends Resource implements Buildable
{
	/**
	 * The enpoint for exchange requests
	 * 
	 * @var string
	 */
	const ENDPOINT = '/exchange';

	/**
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#exchanges-exchange-currency Official API documentation
	 */
	public function create(array $json)
	{
		return $this->client->post(self::ENDPOINT, ['json' => $json]);
	}

	/**
	 * @return \tbclla\Revolut\Builders\ExchangeBuilder
	 */
	public function build()
	{
		return new ExchangeBuilder($this, $this->client->generateRequestId());
	}
}
