<?php

namespace tbclla\Revolut;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use tbclla\Revolut\Exceptions\ApiException;
use tbclla\Revolut\Interfaces\MakesHttpRequests;

class GuzzleHttpClient implements MakesHttpRequests
{
	private $client;

	public function __construct()
	{
		$this->client = new Client();
	}

	/**
	 * Undocumented function
	 *
	 * @param string $url
	 * @param array $options
	 * @return array
	 */
	public function post(string $url, array $options = [])
	{
		return $this->send('POST', $url, $options);
	}

	/**
	 * Undocumented function
	 *
	 * @param string $url
	 * @param array $options
	 * @return array|null
	 */
	public function get(string $url, array $options = [])
	{
		return $this->send('GET', $url, $options);
	}

	/**
	 * Undocumented function
	 *
	 * @param string $url
	 * @param array $options
	 * @return void
	 */
	public function delete(string $url, array $options = []) : void
	{
		$this->send('DELETE', $url, $options);
	}

	/**
	 * Undocumented function
	 *
	 * @param string $method
	 * @param string $url
	 * @param array $options
	 * @return array|null
	 */
	private function send(string $method, string $url, array $options)
	{
		try {
			$response = $this->client->request($method, $url, $options);
		} catch (BadResponseException $e) {
			throw new ApiException('Failed to request access token. ' . $e->getResponse()->getBody(), $e->getCode(), $e);
		}

		return json_decode($response->getBody(), true);
	}
}
