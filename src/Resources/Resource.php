<?php

namespace tbclla\Revolut\Resources;

use tbclla\Revolut\Client;

abstract class Resource
{
    /**
     * The Revolut client
     * 
     * @var \tbclla\Revolut\Client;
     */
    protected $client;

    /**
     * Create a new API resource wrapper
     *
     * @param \tbclla\Revolut\Client $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
