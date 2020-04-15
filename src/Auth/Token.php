<?php

namespace tbclla\Revolut\Auth;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use tbclla\Revolut\Traits\Encryptable;

abstract class Token extends Model
{
	use Encryptable;

	/**
	 * Whether or not to use timestamps
	 * 
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The attributes that are fillable
	 * 
	 * @var array
	 */
	protected $fillable = ['value'];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'is_encrypted' => 'boolean',
		'expires_at' => 'datetime',
		'created_at' => 'datetime',
	];

	/**
	 * The "booting" method of the model.
	 * 
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		static::creating(function($model) {
			$model->type = static::getType();
			$model->expires_at = static::getExpiration();
		});
		
		static::addGlobalScope('type', function(Builder $builder) {
			$builder->whereType(static::getType());
		});
	}

	/**
	 * Get the name of the tokens table
	 * 
	 * @return string
	 */
	public function getTable()
	{
		return config('revolut.tokens_table');
	}

	/**
	 * Check if the token has expired
	 * 
	 * @return bool
	 */
	public function hasExpired()
	{
		return $this->expires_at ? $this->expires_at < now() : false;
	}

	/**
	 * Scope a query to only inlcude active tokens
	 * 
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param bool  $isActive
	 * @return  \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeActive($query, bool $isActive = true)
	{
		$col = 'expires_at';

		return $isActive 
			? $query->where($col, '>', now())->orWhereNull($col)
			: $query->where($col, '<=', now());
	}

	/**
	 * Delete all expired access tokens
	 * 
	 * @return int The number of deleted tokens
	 */
	public static function clearExpired()
	{
		return (int) self::active(false)->delete();
	}
}
