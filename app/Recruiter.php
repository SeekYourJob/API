<?php

namespace CVS;

use Illuminate\Database\Eloquent\Model;

class Recruiter extends Model
{
	protected $table = 'recruiters';
	protected $guarded = ['id'];
	protected $hidden = ['id', 'company_id'];
	protected $appends = ['ido'];

	public function user()
	{
		return $this->morphOne(User::class, 'profile');
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function interviews()
	{
		return $this->hasMany(Interview::class);
	}

	public function getIdoAttribute()
	{
		return app('Optimus')->encode($this->id);
	}

	public static function getAllIdos()
	{
		$recruitersIdos = [];
		$recruiters = self::with('user')->get();
		foreach($recruiters as $recruiter)
			$recruitersIdos[] = $recruiter->user->ido;

		return $recruitersIdos;
	}
}
