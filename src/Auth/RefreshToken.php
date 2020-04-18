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
        return config('revolut.expire_api_access', false)
            ? self::PSD2expiration()
            : null;
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

    /**
     * Calculate the expiration date 
     * 
     * An expiration should be set if the account is subject to PSD2 regulation,
     * under which access to the API expires after 90 days.
     * When access to the API expires, existing access tokens may be revoked.
     * The refresh token should be treated as expired premajurely, to prevent it from
     * being used to request access tokens which may be revoked before their default
     * lifetime has expired.
     *
     * @see https://developer.revolut.com/docs/business-api/#getting-started-usage-and-limits
     * @return \Carbon\Carbon
     */
    private static function PSD2expiration()
    {
        return now()->addDays(90)->subMinutes(AccessToken::TTL);
    }
}
