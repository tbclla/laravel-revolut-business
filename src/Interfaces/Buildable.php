<?php

namespace tbclla\Revolut\Interfaces;

interface Buildable
{
	public function create(array $json);

	public function build();
}
