<?php

namespace tbclla\Revolut\Console\Commands;

use Illuminate\Console\Command;
use tbclla\Revolut\Auth\Requests\AuthorizationCodeRequest;
use tbclla\Revolut\Exceptions\RevolutException;

class AuthorizeCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'revolut:authorize {--redirect= : A destination to be redirected to after the authorization}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Authorize API access for you app';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$redirect = $this->option('redirect') ?? null;

		if (!$route = config('revolut.auth_route.name')) {
			$this->error('Route name invalid or missing');
			$this->error('Check you configuration and verify that "auth_route.name" is valid');
			return;
		}

		$url = route($route, ['after_success' => $redirect]);

		$this->info('Follow the link to complete the authorization:');
		$this->line('<fg=black;bg=yellow>' . $url . '</>');
	}
}
