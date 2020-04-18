<?php

namespace tbclla\Revolut\Providers;

use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Support\ServiceProvider;
use tbclla\Revolut\Auth\ClientAssertion;
use tbclla\Revolut\Auth\Requests\AuthorizationCodeRequest;
use tbclla\Revolut\Client;
use tbclla\Revolut\Console\Commands\AccessTokenCommand;
use tbclla\Revolut\Console\Commands\AuthorizeCommand;
use tbclla\Revolut\Console\Commands\CleanupCommand;
use tbclla\Revolut\Console\Commands\JWTCommand;
use tbclla\Revolut\Console\Commands\ResetCommand;
use tbclla\Revolut\GuzzleHttpClient;
use tbclla\Revolut\Interfaces\MakesHttpRequests;
use tbclla\Revolut\Interfaces\TokenRepository;
use tbclla\Revolut\Repositories\CacheTokenRepository;
use tbclla\Revolut\Repositories\DatabaseTokenRepository;

class RevolutServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CacheTokenRepository::class, function() {
            return new CacheTokenRepository(
                resolve(CacheFactory::class),
                config('revolut.tokens.cache.driver')
            );
        });

        $this->app->bind(TokenRepository::class, function() {
            return config('revolut.tokens.store') === 'database'
                ? new DatabaseTokenRepository
                :  resolve(CacheTokenRepository::class);
        });

        $this->app->bind(MakesHttpRequests::class, GuzzleHttpClient::class);

        $this->app->bind(ClientAssertion::class, function() {
            return new ClientAssertion(
                config('revolut.client_id'),
                config('revolut.private_key'),
                config('revolut.redirect_uri')
            );
        });

        $this->app->bind(AuthorizationCodeRequest::class, function() {
            return new AuthorizationCodeRequest(
                config('revolut.client_id'),
                config('revolut.redirect_uri'),
                config('revolut.sandbox')
            );
        });

        $this->app->singleton('revolut', function() {
            return resolve(Client::class);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/revolut.php' => config_path('revolut.php')
        ]);

        if (config('revolut.tokens.store') === 'database') {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
        
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                JWTCommand::class,
                CleanupCommand::class,
                ResetCommand::class,
                AuthorizeCommand::class,
                AccessTokenCommand::class,
            ]);
        }
    }
}
