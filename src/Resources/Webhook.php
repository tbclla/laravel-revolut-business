<?php

namespace tbclla\Revolut\Resources;

class Webhook extends Resource
{
	/**
	 * The enpoint for webhook requests
	 * 
	 * @var string
	 * 
	 * @return 
	 */
	const ENDPOINT = '/webhook';

	/**
	 * Create a webhook
	 * 
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#web-hooks-setting-up-a-web-hook Official API documentation
	 * @param string $url call back url of the client system, https is the supported protocol
	 */
	public function create(string $url)
	{
		return $this->client->post(self::ENDPOINT, ['json' => ['url' => $url]]);
	}

	/**
	 * Delete the webhook
	 * 
	 * @see https://revolut-engineering.github.io/api-docs/business-api/#web-hooks-deleting-a-web-hook Official API documentation
	 * @return void
	 */
	public function delete() : void
	{
		$this->client->delete(self::ENDPOINT);
	}
}
