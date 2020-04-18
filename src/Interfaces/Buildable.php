<?php

namespace tbclla\Revolut\Interfaces;

interface Buildable
{
    /**
     * Create a resource
     *
     * @param array $json The request body
     * @return array The response body
     * @throws \tbclla\Revolut\Exceptions\ApiException if the client responded with a 4xx-5xx response
     * @throws \tbclla\Revolut\Exceptions\AppUnauthorizedException if the app needs to be re-authorized
     */
    public function create(array $json);

    /**
     * Get a buildable instance
     *
     * @return \tbclla\Revolut\Builders\Builder
     */
    public function build();
}
