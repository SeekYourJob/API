<?php

namespace CVS;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
	protected $table = 'candidates';
	protected $guarded = ['id'];
	protected $hidden = [];

	public function user()
	{
		return $this->morphOne(User::class, 'profile');
	}
}
