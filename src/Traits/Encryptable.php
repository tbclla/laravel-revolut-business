<?php

namespace tbclla\Revolut\Traits;

use Illuminate\Support\Facades\Crypt;

trait Encryptable
{
	/**
	 * the 'boot' method
	 * 
	 * @return void
	 */
	public static function bootEncryptable()
	{
		static::saving(function ($model) {
			if (config('revolut.encrypt_tokens', true)) {
				$model->is_encrypted = true;
				$model->value = $model->encryptValue();
			}
        });
	}

	/**
     * Get the decrypted value attribute.
     *
     * @param  string  $value
     * @return string
     */
	public function getValueAttribute($value)
	{
		return $this->is_encrypted ? $this->decryptValue() : $value;
	}

	/**
	 * Encrypt the value attribute of the model
	 * 
	 * @return string
	 */
	private function encryptValue()
	{
		return Crypt::encrypt($this->attributes['value']);
	}

	/**
	 * Decrypt the value attribute of the model
	 * 
	 * @return string
	 */
	private function decryptValue()
	{
		return Crypt::decrypt($this->attributes['value']);
	}
}
