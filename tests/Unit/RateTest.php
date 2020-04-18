<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Client;
use tbclla\Revolut\Resources\Rate;

class RateTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = $this->mock(Client::class);
        $this->rate = new Rate($this->mockClient);
    }

    /** @test */
    public function get_rate_without_amount()
    {
        $this->mockClient->shouldReceive()->get(Rate::ENDPOINT, [
            'query' => [
                'from' => 'USD',
                'to' => 'EUR',
                'amount' => 1
            ]
        ]);

        $this->rate->get('USD', 'EUR');
    }

    /** @test */
    public function get_rate_with_amount()
    {
        $this->mockClient->shouldReceive()->get(Rate::ENDPOINT, [
            'query' => [
                'from' => 'USD',
                'to' => 'EUR',
                'amount' => 55.34
            ]
        ]);

        $this->rate->get('USD', 'EUR', 55.34);
    }
}
