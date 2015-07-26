<?php

namespace CVS;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	protected $table = 'companies';
	protected $guarded = ['id'];
	protected $hidden = [];

	public function recruiters()
	{
		return $this->hasMany(Recruiter::class);
	}
}
