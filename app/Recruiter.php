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
		return $this->morphOne(User::class, 'profile');
	}

	public function documents()
	{
		return $this->morphMany(Document::class, 'profile');
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}
}
