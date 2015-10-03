<?php

namespace CVS;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
	protected $table = 'slots';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $appends = ['ido'];

	public function interviews()
	{
		return $this->hasMany(Interview::class);
	}

	public function getIdoAttribute()
	{
		return app('Optimus')->encode($this->id);
	}
}
