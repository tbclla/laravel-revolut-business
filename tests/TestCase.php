<?php

namespace tbclla\Revolut\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
	protected function getPackageProviders($app)
	{
		return ['tbclla\Revolut\Providers\RevolutServiceProvider'];
	}

	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{
		$app['config']->set('revolut.sandbox', true);
		$app['config']->set('revolut.encrypt_tokens', true);
		$app['config']->set('revolut.tokens_table', 'revolut_tokens');
		$app['config']->set('revolut.client_id', env('REVOLUT_CLIENT_ID'));
		$app['config']->set('revolut.private_key', env('REVOLUT_PRIVATE_KEY'));
		$app['config']->set('revolut.redirect_uri', env('REVOLUT_REDIRECT_URI'));
		$app['config']->set('revolut.auth_route.name', 'revolut-authorization');
		$app['config']->set('revolut.auth_route.middleware', []);
	}
}
