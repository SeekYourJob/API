<?php

namespace CVS;

use Illuminate\Database\Eloquent\Model;

class Recruiter extends Model
{
	protected $table = 'recruiters';
	protected $guarded = ['id'];
	protected $hidden = [];

	public function user()
	{
		return $this->morphOne('CVS\User', 'profile');
	}
}
