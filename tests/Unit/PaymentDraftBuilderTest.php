<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Builders\PaymentDraftBuilder;
use tbclla\Revolut\Resources\PaymentDraft;

class PaymentDraftBuilderTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->draft = resolve(PaymentDraft::class);
        $this->builder = new PaymentDraftBuilder($this->draft);
    }

    /** @test */
    public function a_payment_draft_can_be_built()
    {
        $payment1 = [
            'currency' => 'EUR',
            'amount' => 123,
            'account_id' => 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab',
            'receiver' => [
                'counterparty_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
                'account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c'
            ],
            'reference' => 'External transfer'
        ];

        $payment2 = [
            'currency' => 'EUR',
            'amount' => 99.12,
            'account_id' => 'db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab',
            'receiver' => [
                'counterparty_id' => 'd56dd396-523b-4613-8cc7-54974c17bcac',
                'account_id' => 'c60ec5b3-c5b9-4cea-936c-fa0306374df5'
            ],
            'reference' => 'External transfer'
        ];


        $this->builder
            ->title('Title of payment')
            ->schedule('2017-10-10')
            ->payments($payment1)
            ->addPayment($payment2);

        $this->assertEquals([
            'title' => 'Title of payment',
            'schedule_for' => '2017-10-10',
            'payments' => array_merge($payment1, $payment2),
        ], $this->builder->toArray());
    }
}
