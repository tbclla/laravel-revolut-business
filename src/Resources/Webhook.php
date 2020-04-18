<?php

namespace tbclla\Revolut\Resources;

class Webhook extends Resource
{
    /**
     * The enpoint for webhook requests
     * 
     * @var string
     */
    const ENDPOINT = '/webhook';

    /**
     * Create a webhook
     * 
     * @see https://revolut-engineering.github.io/api-docs/business-api/#web-hooks-setting-up-a-web-hook Official API documentation
     * @param string $url call back url of the client system, https is the supported protocol
     * @return array The response body
     * @throws \tbclla\Revolut\Exceptions\ApiException if the client responded with a 4xx-5xx response
     * @throws \tbclla\Revolut\Exceptions\AppUnauthorizedException if the app needs to be re-authorized
     */
    public function create(string $url)
    {
        return $this->client->post(self::ENDPOINT, ['json' => ['url' => $url]]);
    }

    /**
     * Delete the webhook
     * 
     * @see https://revolut-engineering.github.io/api-docs/business-api/#web-hooks-deleting-a-web-hook Official API documentation
     * @return void
     * @throws \tbclla\Revolut\Exceptions\ApiException if the client responded with a 4xx-5xx response
     * @throws \tbclla\Revolut\Exceptions\AppUnauthorizedException if the app needs to be re-authorized
     */
    public function delete() : void
    {
        $this->client->delete(self::ENDPOINT);
    }
}
