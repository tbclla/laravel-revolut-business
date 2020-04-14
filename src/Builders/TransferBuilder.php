<?php

namespace tbclla\Revolut\Builders;

class TransferBuilder extends Builder
{
	/**
	 * The source account ID
	 *
	 * @var string
	 */
	public $source_account_id;

	/**
	 * The target account ID
	 *
	 * @var string
	 */
	public $target_account_id;

	/**
	 * The amount
	 *
	 * @var float
	 */
	public $amount;

	/**
	 * The currency in 3-letter ISO format
	 *
	 * @var string
	 */
	public $currency;

	/**
	 * An optional reference
	 *
	 * @var string
	 */
	public $reference;

	/**
	 * The unique request ID
	 *
	 * @var string
	 */
	public $request_id;

	/**
	 * Set the source account ID
	 *
	 * @param string $id
	 * @return self
	 */
	public function sourceAccount(string $id)
	{
		return $this->setAttribute('source_account_id', $id);
	}

	/**
	 * Set the target account ID
	 *
	 * @param string $id
	 * @return self
	 */
	public function targetAccount(string $id)
	{
		return $this->setAttribute('target_account_id', $id);
	}

	/**
	 * Set the transfer amount
	 *
	 * @param float $amount
	 * @return self
	 */
	public function amount(float $amount)
	{
		return $this->setAttribute('amount', $amount);
	}

	/**
	 * Set the transfer currency
	 *
	 * @param string $currency
	 * @return self
	 */
	public function currency(string $currency)
	{
		return $this->setAttribute('currency', $currency);
	}

	/**
	 * Set the optional transfer reference
	 *
	 * @param string $reference
	 * @return self
	 */
	public function reference(string $reference)
	{
		return $this->setAttribute('reference', $reference);
	}
}
