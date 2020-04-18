<?php

namespace tbclla\Revolut\Auth;

use tbclla\Revolut\Auth\Token;
use tbclla\Revolut\Interfaces\GrantsAccessTokens;
use tbclla\Revolut\Interfaces\PersistableToken;

class RefreshToken extends Token implements GrantsAccessTokens, PersistableToken
{
    /**
     * The type of the token
     * 
     * @var string
     */
    const TYPE = 'refresh_token';

    /**
     * The grant type of the token
     * 
     * @var string
     */
    const GRANT_TYPE = 'refresh_token';

    public function getValue()
    {
        return $this->value;
    }

    public static function getType()
    {
        return self::TYPE;
    }

    public static function getGrantType()
    {
        return self::GRANT_TYPE;
    }

    public static function getExpiration()
    {
        return null;
    }

    /**
     * Delete all expired refresh tokens
     * 
     * @return int The number of deleted tokens
     */
    public static function clearExpired()
    {
        $latest = self::latest()->select('id')->first();

        return (int) self::where('id', '!=', $latest->id)->delete();
    }
}
