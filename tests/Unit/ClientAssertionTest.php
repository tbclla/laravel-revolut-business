<?php

namespace tbclla\Revolut\Tests;

use Firebase\JWT\JWT;
use tbclla\Revolut\Auth\ClientAssertion;
use tbclla\Revolut\Exceptions\ConfigurationException;

class ClientAssertionTest extends TestCase
{
	/** @test */
	public function a_client_assertion_builds_a_valid_json_web_token()
	{
		$privateKey = config('revolut.private_key');
		$publicKey = file_get_contents(env('REVOLUT_PUBLIC_KEY'));

		$assertion = new ClientAssertion('abc123', $privateKey, 'http://example.test');
		$decoded  = (array) JWT::decode($assertion->build(), $publicKey, [ClientAssertion::ALGORYTHM]);

		$this->assertEquals('abc123', $decoded['sub']);
		$this->assertEquals('example.test', $decoded['iss']);
	}

	/** @test */
	public function an_invalid_private_key_throws_a_configurationException()
	{
		$this->expectException(ConfigurationException::class);

		$assertion = new ClientAssertion('abc123', '', 'http://example.test');
		$assertion->build();
	}

	/** @test */
	public function an_invalid_redirect_uri_throws_a_configurationException()
	{
		$this->expectException(ConfigurationException::class);

		$assertion = new ClientAssertion('ab123', config('revolut.private_key'), '');
		$assertion->build();
	}
}
