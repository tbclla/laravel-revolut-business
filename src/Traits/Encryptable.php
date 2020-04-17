<?php

namespace tbclla\Revolut\Traits;

use Illuminate\Support\Facades\Crypt;

trait Encryptable
{
	/**
	 * Set the value attribute
	 *
	 * @param string $value
	 * @return void
	 */
	public function setValueAttribute(string $value)
	{
		$encrypt = config('revolut.encrypt_tokens', true);
		
		$this->attributes['value'] = $encrypt ? encrypt($value) : $value;
		$this->attributes['is_encrypted'] = $encrypt;
	}

	/**
	 * Get the value attribute.
	 * 
	 * @param  string  $value
	 * @return string
	 */
	public function getValueAttribute(string $value)
	{
		return $this->is_encrypted ? decrypt($value) : $value;
	}
}
