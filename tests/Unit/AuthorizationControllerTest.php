<?php

namespace tbclla\Revolut\Tests;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use tbclla\Revolut\Auth\AuthorizationCode;
use tbclla\Revolut\Auth\Requests\AuthorizationCodeRequest;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Controllers\AuthorizationController;

class AuthorizationControllerTest extends TestCase
{
	/** @test */
	public function creating_an_authorization_request_creates_a_session()
	{
		$authCodeRequest = resolve(AuthorizationCodeRequest::class);
		$state = $authCodeRequest->state;
		$controller = new AuthorizationController;

		$controller->create(new Request, $authCodeRequest);

		$this->assertTrue(session()->has($state));
	}

	/** @test */
	public function creating_an_authorization_request_with_a_redirect_url_creates_a_session_with_this_url()
	{
		$authCodeRequest = resolve(AuthorizationCodeRequest::class);
		$state = $authCodeRequest->state;
		$controller = new AuthorizationController;
		$url = 'http://test.com/redirect';

		$request = Request::create('', 'GET', ['after_success' => $url]);

		$controller->create($request, $authCodeRequest);

		$this->assertEquals($url, session($state));
	}

	/** @test */
	public function creating_an_authorization_request_redirects_to_the_oauth_flow()
	{
		$authCodeRequest = resolve(AuthorizationCodeRequest::class);
		$controller = new AuthorizationController;

		$response = $controller->create(new Request, $authCodeRequest);

		$this->assertEquals(302, $response->getStatusCode());
	}

	/** @test */
	public function an_authorization_response_without_a_code_aborts()
	{
		$tokenManager = resolve(TokenManager::class);
		$controller = new AuthorizationController;
		$request = Request::create('', 'GET', ['state' => '123']);

		try {
			$controller->store($request, $tokenManager);
		} catch (HttpException $e) {
			
		}

		$this->assertEquals(405, $e->getStatusCode());
	}

	/** @test */
	public function an_authorization_response_without_a_state_aborts()
	{
		$tokenManager = resolve(TokenManager::class);
		$controller = new AuthorizationController;
		$request = Request::create('', 'GET', ['code' => '123']);

		try {
			$controller->store($request, $tokenManager);
		} catch (HttpException $e) {
		}

		$this->assertEquals(405, $e->getStatusCode());
	}

	/** @test */
	public function an_authorization_response_without_a_valid_state_aborts()
	{
		$tokenManager = resolve(TokenManager::class);
		$controller = new AuthorizationController;
		$request = Request::create('', 'GET', [
			'code' => '123',
			'state' => '123',
		]);

		try {
			$controller->store($request, $tokenManager);
		} catch (HttpException $e) {
		}

		$this->assertEquals(405, $e->getStatusCode());
	}

	/** @test */
	public function a_successful_auth_request_triggers_an_access_token_request()
	{
		$tokenManager = $this->mock(TokenManager::class);
		$tokenManager->shouldReceive()->requestAccessToken(AuthorizationCode::class);

		$request = Request::create('', 'GET', [
			'code' => '123',
			'state' => '123',
		]);

		session(['123' => false]);

		$controller = new AuthorizationController;

		$response = $controller->store($request, $tokenManager);

		$this->assertEquals(200, $response->getStatusCode());
	}
}
