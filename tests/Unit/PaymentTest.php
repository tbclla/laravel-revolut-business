<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Client;
use tbclla\Revolut\Resources\Payment;

class PaymentTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = $this->mock(Client::class);
        $this->payment = new Payment($this->mockClient);
    }

    /** @test */
    public function a_payment_can_be_created()
    {
        $data = [
            'request_id' => 'e0cbf84637264ee082a848b',
            'account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c',
            'receiver' => [
                'counterparty_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
                'account_id' => 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab'
            ],
            'amount' => 123.11,
            'currency' => 'EUR',
            'reference' => 'Invoice payment #123'
        ];

        $this->mockClient->shouldReceive()->post(Payment::ENDPOINT, [
            'json' => $data
        ]);

        $this->payment->create($data);
    }

    /** @test */
    public function a_payment_can_be_scheduled()
    {
        $date = now()->addDays(7)->format('Y-m-d');

        $data = [
            'request_id' => 'e0cbf84637264ee082a848b',
            'account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c',
            'receiver' => [
                'counterparty_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
                'account_id' => 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab'
            ],
            'amount' => 123.11,
            'currency' => 'EUR',
            'reference' => 'Invoice payment #123'
        ];

        $this->mockClient->shouldReceive()->post(Payment::ENDPOINT, [
            'json' => array_merge($data, ['schedule_for' => $date])
        ]);

        $this->payment->schedule($data, $date);
    }

    /** @test */
    public function a_scheduled_payment_can_be_cancelled()
    {
        $id = '62b61a4f-fb09-4e87-b0ab-b66c85f5485c';

        $this->mockClient->shouldReceive()->delete('/transaction/' . $id);

        $this->payment->cancel($id);
    }
}
