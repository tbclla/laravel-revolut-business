<?php

namespace tbclla\Revolut\Console\Commands;

use Illuminate\Console\Command;
use tbclla\Revolut\Auth\AccessToken;
use Illuminate\Support\Str;
use tbclla\Revolut\Auth\RefreshToken;

class CleanupCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'revolut:cleanup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete all expired Revolut access tokens and refresh tokens.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$accessTokens = AccessToken::clearExpired();
		$this->info('Deleted ' . $accessTokens . ' expired access ' . Str::plural('token', $accessTokens));

		$refreshTokens = RefreshToken::clearExpired();
		$this->info('Deleted ' . $refreshTokens . ' expired refresh ' . Str::plural('token', $refreshTokens));
	}
}
