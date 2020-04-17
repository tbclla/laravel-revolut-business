<?php

namespace tbclla\Revolut\Repositories;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\RefreshToken;
use tbclla\Revolut\Interfaces\PersistableToken;
use tbclla\Revolut\Interfaces\TokenRepository;
use Illuminate\Cache\Repository as Cache;

class CacheTokenRepository implements TokenRepository
{
	/**
	 * The cache key prefix
	 * 
	 * @var string
	 */
	const PREFIX = 'revolut_';

	/**
	 * @param \Illuminate\Cache\Repository $cache
	 */
	public function __construct(Cache $cache)
	{
		$this->cache = $cache;
	}

	public function getAccessToken()
	{
		return $this->getToken(AccessToken::TYPE);
	}

	public function getRefreshToken()
	{
		return $this->getToken(RefreshToken::TYPE);
	}

	public function createAccessToken(string $value)
	{
		$this->createToken($accessToken = new AccessToken([
			'value' => $value
		]));

		return $accessToken;
	}

	public function createRefreshToken(string $value)
	{
		$this->createToken($refreshToken = new RefreshToken([
			'value' => $value
		]));

		return $refreshToken;
	}

	/**
	 * Get a token from the cache
	 *
	 * @param string $type
	 * @return \tbclla\Revolut\Interfaces\PersistableToken|null
	 */
	private function getToken($type)
	{
		return $this->cache->get($this->getKey($type));
	}

	/**
	 * Put the token into the cache
	 * 
	 * @param \tbclla\Revolut\Interfaces\PersistableToken $token
	 */
	private function createToken(PersistableToken $token)
	{
		$this->cache->put(
			$this->getKey($token->getType()),
			$token,
			$token->getExpiration()
		);
	}

	/**
	 * Get the cache key
	 *
	 * @param string $type
	 * @return string
	 */
	public function getKey(string $type)
	{
		return self::PREFIX . $type;
	}
}
