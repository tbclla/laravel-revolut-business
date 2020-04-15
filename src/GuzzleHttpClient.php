<?php

namespace tbclla\Revolut;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use tbclla\Revolut\Exceptions\ApiException;
use tbclla\Revolut\Interfaces\MakesHttpRequests;

class GuzzleHttpClient implements MakesHttpRequests
{
	/**
	 * @var \GuzzleHttp\Client
	 */
	private $client;

	/**
	 * @return void
	 */
	public function __construct()
	{
		$this->client = new Client();
	}

	public function post(string $url, array $options = [])
	{
		return $this->send('POST', $url, $options);
	}

	public function get(string $url, array $options = [])
	{
		return $this->send('GET', $url, $options);
	}

	public function delete(string $url, array $options = []) : void
	{
		$this->send('DELETE', $url, $options);
	}

	/**
	 * Perform a request
	 * 
	 * @param string $method The request method
	 * @param string $url The request url
	 * @param array $options The request options
	 * @return array|null The response body
	 * @throws \tbclla\Revolut\Exceptions\ApiException API returned a 4xx or 5xx response code
	 */
	private function send(string $method, string $url, array $options)
	{
		try {
			$response = $this->client->request($method, $url, $options);
		} catch (BadResponseException $e) {
			throw new ApiException($e->getMessage(), $e->getCode(), $e);
		}

		return json_decode($response->getBody(), true);
	}
}
