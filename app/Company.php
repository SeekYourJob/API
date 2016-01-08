<?php

namespace CVS;

use CVS\Traits\ObfuscatedIdTrait;
use DB;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	use ObfuscatedIdTrait;

	protected $table = 'companies';
	protected $guarded = ['id'];
	protected $hidden = ['id', 'created_at', 'updated_at'];
	protected $appends = ['ido'];
	protected $casts = [
		'job_types' => 'array',
	];

	public function recruiters()
	{
		return $this->hasMany(Recruiter::class);
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

	public static function getInterviewsForCompany(Company $company, $candidate = false)
	{
		$companyToReturn = [
			'company' => $company,
			'interviews' => [],
			'summary' => [],
		];

		$slots = DB::select('
	        SELECT s.id AS slot_id, COUNT(s.id) AS total_slots, CAST(SUM(IF(i.id IS NOT NULL AND i.candidate_id IS NULL, 1, 0)) AS UNSIGNED INTEGER) AS free_slots, GROUP_CONCAT(i.candidate_id) AS candidate_ids
	        FROM slots s
	        LEFT OUTER JOIN interviews i ON s.id = i.slot_id AND i.company_id = ?
	        GROUP BY s.id ORDER BY slot_id ASC', [$company->id]
		);

		foreach ($slots as &$slot) {
			// Adding the Slot ido
			$slot->slot_ido = app('Hashids')->encode($slot->slot_id);

			// Converting raw SQL candidate_ids to PHP array
			if (!is_null($slot->candidate_ids)) {
				$slot->candidate_ids = array_map('intval', explode(',', $slot->candidate_ids));

				// Getting the Interview if a Candidate is passed and matched an interview
				if ($candidate) {
					// Checking if the Candidate has an interview with the current Company
					if (in_array($candidate->id, $slot->candidate_ids)) {
						$companyToReturn['hasInterviewWithCandidate'] = true;
						$slot->interview = Interview::where('company_id', $company->id)
							->where('candidate_id', $candidate->id)
							->first()->ido;
					}
				}
			}

			// Removing candidate_ids if a Candidate is passed
			if ($candidate) {
				// Checking if the Candidate has an interview with another Company at this slot
				if (!isset($slot->interview) && array_key_exists($slot->slot_id, $candidate->registered_slots)) {
					$slot->already_registered = $candidate->registered_slots[$slot->slot_id];
				}

				unset($slot->candidate_ids);
			}

			if (!isset($companyToReturn['summary']['total_slots']))
				$companyToReturn['summary']['total_slots'] = 0;
			$companyToReturn['summary']['total_slots'] = $companyToReturn['summary']['total_slots'] + $slot->total_slots;

			if (!isset($companyToReturn['summary']['free_slots']))
				$companyToReturn['summary']['free_slots'] = 0;
			$companyToReturn['summary']['free_slots'] = $companyToReturn['summary']['free_slots'] + $slot->free_slots;

			unset($slot->slot_id);
		}

		$companyToReturn['interviews'] = $slots;

		if ($companyToReturn['summary']['free_slots'] == $companyToReturn['summary']['total_slots']) {
			$companyToReturn['summary']['remaining_percentage'] = 100; $companyToReturn['summary']['taken_percentage'] = 0;

		} else {
			if ($companyToReturn['summary']['free_slots'] == 0) {
				$companyToReturn['summary']['remaining_percentage'] = 0; $companyToReturn['summary']['taken_percentage'] = 100;

			} else {
				$companyToReturn['summary']['remaining_percentage'] = ((100 * $companyToReturn['summary']['free_slots']) / $companyToReturn['summary']['total_slots']); $companyToReturn['summary']['taken_percentage'] = ((($companyToReturn['summary']['total_slots'] - $companyToReturn['summary']['free_slots']) * 100) / $companyToReturn['summary']['total_slots']);
			}
		}

		return $companyToReturn;
	}

	public static function getInterviewsGroupedByCompanies($candidate = false)
	{
		$response = ['slots' => Slot::all(), 'companies' => []];

		foreach(self::all() as &$company) {
			$response['companies'][] = self::getInterviewsForCompany($company, $candidate);
		}


		return $response;
	}

	public static function getInterviewsGroupedByCompaniesForCandidate(Candidate $candidate)
	{
		return self::getInterviewsGroupedByCompanies($candidate);
	}
}
