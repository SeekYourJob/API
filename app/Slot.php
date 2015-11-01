<?php

namespace CVS;

use CVS\Traits\ObfuscatedIdTrait;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
	use ObfuscatedIdTrait;

	protected $table = 'slots';
	protected $guarded = ['id'];
	protected $hidden = ['id'];
	protected $appends = ['ido', 'begins_at_formatted', 'ends_at_formatted'];

	public function interviews()
	{
		return $this->hasMany(Interview::class);
	}

	public function getBeginsAtFormattedAttribute()
	{
		return date("H:i", strtotime($this->begins_at));
	}

	public function getEndsAtFormattedAttribute()
	{
		return date("H:i", strtotime($this->ends_at));
	}
}
