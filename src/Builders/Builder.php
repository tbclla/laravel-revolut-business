<?php

namespace tbclla\Revolut\Builders;

use tbclla\Revolut\Interfaces\Buildable;

abstract Class Builder
{
	/**
	 * The API resource
	 *
	 * @var \tbclla\Revolut\Interfaces\Buildable
	 */
	private $resource;

	/**
	 * The unique ID of the request
	 *
	 * @var string
	 */
	public $request_id;

	/**
	 * Create a new builder instance
	 *
	 * @param \tbclla\Revolut\Resources\Resource $resource
	 * @param string $requestId
	 * @return void
	 */
	public function __construct(Buildable $resource, string $requestId = null)
	{
		$this->resource = $resource;
		$this->request_id = $requestId;
	}

	/**
	 * Execute the create request
	 *
	 * @return array
	 */
	public function create()
	{
		return $this->resource->create($this->toArray());
	}

	/**
	 * Set the unique equest ID
	 *
	 * @param string $id
	 * @return self
	 */
	public function requestId(string $id)
	{
		return $this->setAttribute('request_id', $id);
	}

	/**
	 * Set the value of an attribute
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @return self
	 */
	protected function setAttribute(string $attribute, $value)
	{
		$this->$attribute = $value;

		return $this;
	}

	/**
	 * Build the request data array
	 *
	 * @return array
	 */
	public function toArray()
	{
		$data = [];

		foreach (get_object_vars($this) as $attribute => $value) {
			if ($attribute != 'resource' and !empty($value)) {
				$data[$attribute] = $value;
			}
		}

		return $data;
	}
}
