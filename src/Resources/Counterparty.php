<?php

namespace tbclla\Revolut\Resources;

use tbclla\Revolut\Builders\CounterpartyBuilder;
use tbclla\Revolut\Interfaces\Buildable;

class Counterparty extends Resource implements Buildable
{
	/**
	 * The enpoint for counterparty requests
	 * 
	 * @var string
	 */
	const ENDPOINT = '/counterparty';

	/**
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#counterparties-add-revolut-counterparty Official API documentation
	 */
	public function create(array $json)
    {
		return $this->client->post(self::ENDPOINT, ['json' => $json]);
	}
	
	/**
     * Get all counterparties
	 *
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#counterparties-get-counterparties Official API documentation
     * @return array
     */
    public function all()
    {
        return $this->client->get('/counterparties');
	}
	
	/**
	 * Get a counterparty by its ID
	 * 
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#counterparties-get-counterparty Official API documentation
	 * @param string $id The counterpary ID in UUID format
	 * @return array
	 */
	public function get(string $id)
    {
        return $this->client->get(self::ENDPOINT . '/' . $id);
	}
	
	/**
	 * Delete a counterparty by its ID
	 * 
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#counterparties-delete-counterparty Official API documentation
	 * @param string $id The counterpary ID in UUID format
	 * @return void
	 */
	public function delete(string $id) : void
    {
        $this->client->delete(self::ENDPOINT . '/' . $id);
	}

	/**
	 * @return \tbclla\Revolut\Builders\CounterpartyBuilder
	 */
	public function build()
	{
		return new CounterpartyBuilder($this);
	}
}
