<?php

namespace tbclla\Revolut\Console\Commands;

use Exception;
use Firebase\JWT\JWT;
use Illuminate\Console\Command;
use tbclla\Revolut\Auth\ClientAssertion;
use tbclla\Revolut\Exceptions\ConfigurationException;

class JWTCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revolut:jwt {--public= : The path to your public key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate a JSON Web Token for Revolut's Oauth process.";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // build the JWT
        $jwt = $this->buildJWT();

        $this->info('Your JSON web token was created successfully:');
        $this->info('<fg=black;bg=yellow>' . $jwt . '</>');

        // optionally, verify the key
        $key = $this->checkPublicKey($this->option('public') ?? null);

        $decoded = JWT::decode($jwt, $key, [ClientAssertion::ALGORYTHM]);

        $headers = ['parameter', 'value'];
        $data = [
            ['issuer', $decoded->iss],
            ['subject', $decoded->sub],
            ['expiry', $decoded->exp],
            ['audience', $decoded->aud],
        ];

        $this->info('Your JWT has been verified and is valid.');
        $this->table($headers, $data);
    }

    /**
     * @return string
     */
    private function buildJWT()
    {
        try {
            $clientAssertion = resolve(ClientAssertion::class);
            return $clientAssertion->build();
        } catch (ConfigurationException $e) {
            $this->error($e->getMessage());
            exit;
        }
    }

    /**
     * @return string
     */
    private function checkPublicKey($key = null)
    {
        try {
            return file_get_contents($key ?? $this->ask('If you want to validate this JWT, enter the path to your private key'));
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return $this->checkPublicKey();
        }
    }
}
