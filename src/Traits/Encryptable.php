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
        $encrypt = config('revolut.tokens.encrypt', true);
        
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
        return (string) $this->is_encrypted ? decrypt($value) : $value;
    }
}
