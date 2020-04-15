<?php

namespace tbclla\Revolut\Interfaces;

interface MakesHttpRequests
{
	/**
	 * Make a 'POST' request
	 * 
	 * @param string $url The request url
	 * @param array $options The request options
	 * @return array The response body
	 * @throws \tbclla\Revolut\Exceptions\ApiException API returned a 4xx or 5xx response code
	 */
	public function post(string $url, array $options = []);

	/**
	 * Make a 'GET' request
	 * 
	 * @param string $url The request url
	 * @param array $options The request options
	 * @return array|null The response body
	 * @throws \tbclla\Revolut\Exceptions\ApiException API returned a 4xx or 5xx response code
	 */
	public function get(string $url, array $options = []);

	/**
	 * Make a 'DELETE' request
	 * 
	 * @param string $url The request url
	 * @param array $options The request options
	 * @return void
	 * @throws \tbclla\Revolut\Exceptions\ApiException API returned a 4xx or 5xx response code
	 */
	public function delete(string $url);
}
