<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Interfaces\MakesHttpRequests;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Client;
use tbclla\Revolut\Exceptions\RevolutException;

class ClientTest extends TestCase
{
    /** @test */
    public function an_invalid_method_call_throws_a_revolutException()
    {
        $this->expectException(RevolutException::class);

        $client = resolve(Client::class);
        $client->history();
    }

    /** @test */
    public function a_client_can_get_an_access_token_from_the_manager_if_none_is_set()
    {
        $tokenManager = $this->mock(TokenManager::class, function ($mock) {
            $mock->shouldReceive()->getAccessToken()->andReturn(new AccessToken([
                'value' => 'example_token'
            ]));
        });

        $httpClient = $this->mock(MakesHttpRequests::class, function ($mock) {
            $mock->shouldReceive()->delete(Client::buildUri('/'), [
                'headers' => [
                    'Authorization' => 'Bearer example_token'
                ]
            ]);
        });

        $client = new Client($tokenManager, $httpClient);

        $client->delete('/');
    }

    /** @test */
    public function a_client_can_request_a_new_access_token_from_the_manager_if_the_current_one_has_expired()
    {
        $expiredToken = new AccessToken(['value' => 'expired_token']);
        $expiredToken->expires_at = now();

        $newToken = new AccessToken(['value' => 'new_token']);

        $tokenManager = $this->mock(TokenManager::class, function($mock) use ($newToken) {
            $mock->shouldReceive()->refreshAccessToken()->andReturn($newToken);
        });

        $httpClient = $this->mock(MakesHttpRequests::class, function($mock) use ($newToken) {
            $mock->shouldReceive()->delete(Client::buildUri('/'), [
                'headers' => [
                    'Authorization' => 'Bearer ' . $newToken->value
                ]
            ]);
        });

        $client = new Client($tokenManager, $httpClient);
        $client->setAccessToken($expiredToken);

        $client->delete('/');
    }

    /** @test */
    public function a_client_can_refresh_its_access_token()
    {
        $tokenManager = $this->mock(TokenManager::class, function($mock) {
            $mock->shouldReceive()->refreshAccessToken()->andReturn(new AccessToken);
        });

        $client = new Client($tokenManager, resolve(MakesHttpRequests::class));

        $client->refreshAccessToken();
    }

    /** @test */
    public function a_client_can_make_a_post_request_to_an_api_endpoint_with_a_bearer_token()
    {
        $accessToken = new AccessToken;
        $accessToken->value = 'example_token';

        $httpClient = $this->mock(MakesHttpRequests::class, function($mock) {
            $mock->shouldReceive()->post(Client::buildUri('/endpoint'), [
                'headers' => [
                    'Authorization' => 'Bearer example_token'
                ]
            ]);
        });

        $client = new Client(resolve(TokenManager::class), $httpClient);
        $client->setAccessToken($accessToken);

        $client->post('/endpoint');
    }

    /** @test */
    public function a_client_can_make_a_get_request_to_an_api_endpoint_with_a_bearer_token()
    {
        $accessToken = new AccessToken;
        $accessToken->value = 'example_token';

        $httpClient = $this->mock(MakesHttpRequests::class, function ($mock) {
            $mock->shouldReceive()->get(Client::buildUri('/endpoint'), [
                'headers' => [
                    'Authorization' => 'Bearer example_token'
                ]
            ]);
        });

        $client = new Client(resolve(TokenManager::class), $httpClient);
        $client->setAccessToken($accessToken);

        $client->get('/endpoint');
    }

    /** @test */
    public function a_client_can_make_a_delete_request_to_an_api_endpoint_with_a_bearer_token()
    {
        $accessToken = new AccessToken;
        $accessToken->value = 'example_token';

        $httpClient = $this->mock(MakesHttpRequests::class, function ($mock) {
            $mock->shouldReceive()->delete(Client::buildUri('/endpoint'), [
                'headers' => [
                    'Authorization' => 'Bearer example_token'
                ]
            ]);
        });

        $client = new Client(resolve(TokenManager::class), $httpClient);
        $client->setAccessToken($accessToken);

        $client->delete('/endpoint');
    }
}
