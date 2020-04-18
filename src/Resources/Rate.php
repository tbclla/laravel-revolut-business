<?php

namespace tbclla\Revolut\Resources;

class Rate extends Resource
{
    /**
     * The enpoint for rate requests
     * 
     * @var string
     */
    const ENDPOINT = '/rate';

    /**
     * Get an exchange rate
     * 
     * @see https://revolut-engineering.github.io/api-docs/business-api/#exchanges-get-exchange-rates Official API documentation
     * @param string $from 3-letter ISO base currency
     * @param string $to 3-letter ISO target currency
     * @param float $amount decimal amount, default is 1.00
     * @return array The response body
     * @throws \tbclla\Revolut\Exceptions\ApiException if the client responded with a 4xx-5xx response
     * @throws \tbclla\Revolut\Exceptions\AppUnauthorizedException if the app needs to be re-authorized
     */
    public function get(string $from, string $to, float $amount = 1)
    {
        return $this->client->get(self::ENDPOINT, [
            'query' => compact('from', 'to', 'amount')
        ]);
    }
}
