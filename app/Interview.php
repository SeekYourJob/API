<?php

namespace CVS;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
	protected $table = 'interviews';
	protected $guarded = ['id'];
	protected $hidden = [];

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function slot()
	{
		return $this->belongsTo(Slot::class);
	}

	public function recruiter()
	{
		return $this->belongsTo(Recruiter::class);
	}

	public function candidate()
	{
		return $this->belongsTo(Candidate::class);
	}
}
