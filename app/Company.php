<?php

namespace CVS;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	protected $table = 'companies';
	protected $guarded = ['id'];
	protected $hidden = ['id'];
	protected $appends = ['ido'];
	protected $casts = [
		'job_types' => 'array',
	];

	public function recruiters()
	{
		return $this->hasMany(Recruiter::class);
	}

	public function getIdoAttribute()
	{
		return app('Optimus')->encode($this->id);
	}

	public static function getIdosGroupedByCompanies()
	{
		$companies = self::with('recruiters.user')->get();

		$groups = [];
		foreach($companies as $company) {
			$companyUsers = [];
			foreach($company->recruiters as $recruiter)
				$companyUsers[] = $recruiter->ido;
			if (!empty($companyUsers))
				$groups[$company->name] = $companyUsers;
		}

		return $groups;
	}
}
