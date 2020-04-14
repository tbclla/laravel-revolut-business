<?php

namespace tbclla\Revolut\Builders;

class ExchangeBuilder extends Builder
{
	/**
	 * Information about the account you want to exchange from
	 *
	 * @var array
	 */
	public $from;

	/**
	 * Information about the account you want to exchange to
	 *
	 * @var array
	 */
	public $to;


	/**
	 * An optional reference
	 *
	 * @var string
	 */
	public $reference;

	/**
	 * Set the outgoing account
	 *
	 * @param string $accountId the account ID
	 * @param string $currency the account currency
	 * @param float $amount the amount of currency to sell (only when selling)
	 * @return self
	 */
	public function from(string $accountId, string $currency, float $amount = null)
	{
		return $this->setAccount('from', $accountId, $currency, $amount);
	}

	/**
	 * Set the receiving account
	 *
	 * @param string $accountId the account ID
	 * @param string $currency the account currency
	 * @param float $amount	the amount of currency to buy (only when buying)
	 * @return self
	 */
	public function to(string $accountId, string $currency, float $amount = null)
	{
		return $this->setAccount('to', $accountId, $currency, $amount);
	}

	/**
	 * Set an optional reference
	 *
	 * @param string $reference
	 * @return self
	 */
	public function reference(string $reference)
	{
		return $this->setAttribute('reference', $reference);
	}

	/**
	 * Set the account information
	 *
	 * @param string $type
	 * @param string $accountId
	 * @param string $currency
	 * @param float $amount
	 * @return self
	 */
	private function setAccount(string $type, string $accountId, string $currency, $amount)
	{
		$account = [
			'account_id' => $accountId,
			'currency' => $currency,
		];

		if ($amount) {
			$account['amount'] = $amount;
		}

		return $this->setAttribute($type, $account);
	}
}
