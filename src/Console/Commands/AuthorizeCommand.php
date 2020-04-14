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
	protected $signature = 'revolut:authorize';

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
		$request = resolve(AuthorizationCodeRequest::class);
		
		try {
			$url = $request->build();
		} catch (RevolutException $e) {
			$this->error($e->getMessage());
			return;
		}

		$this->info('Your authorization request has been created.');
		$this->line('Follow the link to complete the authorization:');
		$this->info('<fg=black;bg=yellow>' . $url . '</>');
		$this->comment('Revolut will automatically redirect you to ' . config('revolut.redirect_uri') . ' at the end of the authorization process.');
	}
}
