<?php

namespace tbclla\Revolut\Repositories;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\RefreshToken;
use tbclla\Revolut\Interfaces\PersistableToken;
use tbclla\Revolut\Interfaces\TokenRepository;
use Illuminate\Contracts\Cache\Factory as CacheFactory;

class CacheTokenRepository implements TokenRepository
{
	/**
	 * The cache key prefix
	 * 
	 * @var string
	 */
	const PREFIX = 'revolut_';

	/**
	 * A cache repository
	 * 
	 * @var \Illuminate\Cache\Repository
	 */
	private $cache;

	/**
	 * @param \Illuminate\Contracts\Cache\Factory $cache
	 * @param string $driver
	 * @return void
	 */
	public function __construct(CacheFactory $cache, string $driver = null)
	{
		$this->cache = $cache->store($driver);
	}

	public function getAccessToken()
	{
		return $this->cache->get($this->getKey(AccessToken::TYPE));
	}

	public function getRefreshToken()
	{
		return $this->cache->get($this->getKey(RefreshToken::TYPE));
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
