<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Client;
use tbclla\Revolut\Resources\PaymentDraft;

class PaymentDraftTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = $this->mock(Client::class);
        $this->draft = new PaymentDraft($this->mockClient);
    }

    /** @test */
    public function a_payment_draft__can_be_created()
    {
        $data = [
            'title' => 'Title of payment',
            'schedule_for' => '2017-10-10',
            'payments' => [
                [
                    'currency' => 'EUR',
                    'amount' => 123,
                    'account_id' => 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab',
                    'receiver' => [
                        'counterparty_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
                        'account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c'
                    ],
                    'reference' => 'External transfer'
                ]
            ]
        ];

        $this->mockClient->shouldReceive()->post(PaymentDraft::ENDPOINT, [
            'json' => $data
        ]);

        $this->draft->create($data);
    }
}
