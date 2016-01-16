<?php

namespace CVS;

use CVS\Traits\ObfuscatedIdTrait;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	use ObfuscatedIdTrait;

	protected $table = 'locations';
	protected $guarded = ['id'];
	protected $hidden = ['id', 'created_at', 'updated_at'];
	protected $appends = ['ido'];

	public static function findByIdo($ido)
	{
		return self::find(app('Hashids')->decode($ido)[0]);
	}
}
