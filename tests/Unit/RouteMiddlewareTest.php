<?php

namespace tbclla\Revolut\Tests;

use Illuminate\Routing\Router;

class RouteMiddlewareTest extends TestCase
{
	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{
		parent::getEnvironmentSetUp($app);

		$router = resolve(Router::class);
		$router->aliasMiddleware('example', ExampleMiddleware::class);
		
		$app['config']->set('revolut.auth_route.middleware', ['example']);
	}

	/** @test */
	public function the_create_auth_route_can_be_protected_with_middleware()
	{
		$mockMiddleware = $this->mock(ExampleMiddleware::class);
		$mockMiddleware->shouldReceive('handle')->once();

		$route = route(config('revolut.auth_route.name'));

		$this->get($route);
	}
}
