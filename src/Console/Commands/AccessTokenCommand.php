<?php

namespace tbclla\Revolut\Console\Commands;

use Illuminate\Console\Command;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Exceptions\AppUnauthorizedException;

class AccessTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revolut:access-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get an active revolut access token.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(TokenManager $manager)
    {
        try {
            $accessToken = $manager->getAccessToken();
        } catch(AppUnauthorizedException $e) {
            $this->error('No valid refresh token found.');
            $this->line("calling 'revolut:authorize' to re-authorize...");
            return $this->call('revolut:authorize');
        }

        $this->info($accessToken->value);
    }
}
