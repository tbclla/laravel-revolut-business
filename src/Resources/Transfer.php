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
     * Create a transfer between accounts of the business with the same currency.
     *
     * @see https://revolut-engineering.github.io/api-docs/business-api/#transfers-create-transfer Official API documentation
     * @param array $json The request parameters
     * @return array
     */
    public function create(array $json)
    {
        return $this->client->post(self::ENDPOINT, ['json' => $json]);
    }

    /**
     * Build the transfer request options
     * 
     * @return \tbclla\Revolut\Builders\TransferBuilder
     */
    public function build()
    {
        return new TransferBuilder($this, $this->client->generateRequestId());
    }
}
