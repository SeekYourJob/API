<?php

namespace CVS;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
	protected $table = 'candidates';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $appends = ['ido'];

	public function user()
	{
		return $this->morphOne(User::class, 'profile');
	}

	public function documents()
	{
		return $this->morphMany(Document::class, 'profile');
	}

	public function getIdoAttribute()
	{
		return app('Optimus')->encode($this->id);
	}
}
