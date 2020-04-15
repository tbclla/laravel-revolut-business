<?php

namespace tbclla\Revolut\Interfaces;

interface Buildable
{
	/**
	 * Create a resource
	 *
	 * @param array $json The request body
	 * @return array The response body
	 */
	public function create(array $json);

	/**
	 * Get a buildable instance
	 *
	 * @return \tbclla\Revolut\Interfaces\Buildable
	 */
	public function build();
}
