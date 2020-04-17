<?php

namespace tbclla\Revolut\Traits;

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
	 * @param string|null $value
	 * @return string
	 */
	public function getValueAttribute($value)
	{
		return $this->is_encrypted ? decrypt($value) : $value;
	}
}
