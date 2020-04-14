<?php

namespace tbclla\Revolut\Interfaces;

interface MakesHttpRequests
{
	public function post(string $url, array $options = []);

	public function get(string $url, array $options = []);

	public function delete(string $url);
}
