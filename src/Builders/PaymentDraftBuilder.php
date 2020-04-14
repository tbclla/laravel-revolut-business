<?php

namespace tbclla\Revolut\Builders;

class PaymentDraftBuilder extends Builder
{
	/**
	 * An optional title
	 *
	 * @var string
	 */
	public $title;

	/**
	 * An optional future date
	 *
	 * @var string
	 */
	public $schedule_for;

	/**
	 * The payments included in the draft
	 *
	 * @var array
	 */
	public $payments;

	/**
	 * Set an optional title for the draft
	 *
	 * @param string $title
	 * @return self
	 */
	public function title(string $title)
	{
		return $this->setAttribute('title', $title);
	}

	/**
	 * Set an optional future date to schedule the payments
	 *
	 * @param string $date
	 * @return self
	 */
	public function schedule(string $date)
	{
		return $this->setAttribute('schedule_for', $date);
	}

	/**
	 * Set the payments included in the draft
	 *
	 * @param array $payments
	 * @return self
	 */
	public function payments(array $payments)
	{
		return $this->setAttribute('payments', $payments);
	}

	/**
	 * Add a payment to the draft
	 *
	 * @param array $payment
	 * @return self
	 */
	public function addPayment(array $payment)
	{
		return $this->payments(array_merge($this->payments ?? [], $payment));
	}
}
