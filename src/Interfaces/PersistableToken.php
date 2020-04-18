<?php

namespace tbclla\Revolut\Interfaces;

interface PersistableToken
{
    /**
     * Get the type of the token
     *
     * @return string
     */
    public static function getType();

    /**
     * Get the expiration date
     *
     * @return \Carbon\Carbon|null
     */
    public static function getExpiration();
}
