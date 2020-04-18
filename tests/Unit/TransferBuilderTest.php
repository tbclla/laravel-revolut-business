<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Builders\TransferBuilder;
use tbclla\Revolut\Resources\Transfer;

class TransferBuilderTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->transfer = resolve(Transfer::class);
        $this->builder = new TransferBuilder($this->transfer);
    }

    /** @test */
    public function a_transfer_can_be_built()
    {
        $this->builder->sourceAccount('bdab1c20-8d8c-430d-b967-87ac01af060c')
            ->targetAccount('5138z40d1-05bb-49c0-b130-75e8cf2f7693')
            ->amount(123.11)
            ->currency('EUR')
            ->reference('Expenses funding')
            ->requestId('d55df8dc-fecc-429c-b000-3ccbd990d0b3');
            
        $this->assertEquals([
            'request_id' => 'e0cbf84637264ee082a848b',
            'source_account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c',
            'target_account_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
            'amount' => 123.11,
            'currency' => 'EUR',
            'reference' => 'Expenses funding',
            'request_id' => 'd55df8dc-fecc-429c-b000-3ccbd990d0b3',
        ], $this->builder->toArray());
    }

    /** @test */
    public function calling_the_builder_from_the_transfer_sets_a_request_id()
    {
        $builder = $this->transfer->build();

        $data = $builder->toArray();

        $this->assertNotEmpty($data['request_id']);
    }
}
