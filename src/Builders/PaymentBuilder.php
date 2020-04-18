<?php

namespace tbclla\Revolut\Builders;

class PaymentBuilder extends Builder
{
    /**
     * The ID of the account to pay from
     * 
     * @var string
     */
    public $account_id;

    /**
     * The receiving party
     *
     * @var array
     */
    public $receiver;

    /**
     * The payment amount
     *
     * @var float
     */
    public $amount;

    /**
     * The payment currency
     *
     * @var string
     */
    public $currency;

    /**
     * An optional payment reference
     *
     * @var string
     */
    public $reference;

    /**
     * An optional date to schedule the payment
     *
     * @var string
     */
    public $schedule_for;

    /**
     * Set the outgoing account ID
     *
     * @param string $accountId the ID of the account to pay from
     * @return self
     */
    public function account(string $accountId)
    {
        return $this->setAttribute('account_id', $accountId);
    }

    /**
     * Set the receiver of the payment
     *
     * @param string $counterpartyId the ID of the receiving counterparty
     * @param string $accountId	the ID of the receiving counterparty's account, only for payments to business counterparties
     * @return self
     */
    public function receiver(string $counterpartyId, string $accountId = null)
    {
        $receiver = ['counterparty_id' => $counterpartyId];

        if ($accountId) {
            $receiver['account_id'] = $accountId;
        }

        return $this->setAttribute('receiver', $receiver);
    }

    /**
     * Set the transaction amount
     *
     * @param float $amount
     * @return self
     */
    public function amount(float $amount)
    {
        return $this->setAttribute('amount', $amount);
    }

    /**
     * Set the transaction currency
     *
     * @param float $amount
     * @return self
     */
    public function currency(string $currency)
    {
        return $this->setAttribute('currency', $currency);
    }

    /**
     * Set an optional textual reference shown on the transaction
     *
     * @param string $reference
     * @return self
     */
    public function reference(string $reference)
    {
        return $this->setAttribute('reference', $reference);
    }

    /**
     * Set an optional date to schedule the payment
     *
     * @param string $date
     * @return self
     */
    public function schedule(string $date)
    {
        return $this->setAttribute('schedule_for', $date);
    }
}
