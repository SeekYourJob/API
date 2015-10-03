<?php

namespace CVS;

use CVS\Enums\InterviewStatus;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
	protected $table = 'interviews';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $append = ['ido'];

	public function getIdoAttribute()
	{
		return app('Optimus')->encode($this->id);
	}

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

	public static function getAllForAllCompanies()
	{
		$allSlots = Slot::all();
		$allCompanies = Company::with(['recruiters.interviews.candidate.user', 'recruiters.user'])->get();

		$allInterviewsByCompany = [];
		foreach ($allCompanies as $company) {
			$allInterviewsByCompany[] = self::getAllForCompany($company, $allSlots);
		}

		return $allInterviewsByCompany;
	}

	public static function getAllForCompany(Company $company, $allSlots = false)
	{
		if ( ! $allSlots) {
			$allSlots = Slot::all();
		}

		$interviewByCompany = [
			'company' => [
				'ido' => $company->ido,
				'name' => $company->name
			],
			'recruiters' => []
		];

		foreach ($company->recruiters as $recruiter) {
			$recruiterToAdd = [
				'ido' => $recruiter->ido,
				'firstname' => $recruiter->user->firstname,
				'lastname' => $recruiter->user->lastname,
				'interviews' => []
			];

			foreach ($allSlots as $slot) {
				// Creating a "default" UNAVAILABLE interview

				$interviewToAdd = [
					'slot' => [
						'ido' => $slot->ido,
						'begins_at' => $slot->begins_at_formatted,
						'ends_at' => $slot->ends_at_formatted
					],
					'status' => InterviewStatus::UNAVAILABLE
				];

				foreach ($recruiter->interviews as $interview) {
					// Check if the recruiter is available for the specified slot
					if ($interview->slot_id === $slot->id) {
						$interviewToAdd['ido'] = $interview->ido;
						$interviewToAdd['status'] = InterviewStatus::FREE;
						if (! is_null($interview->candidate)) {
							$interviewToAdd['status'] = InterviewStatus::TAKEN;
							$interviewToAdd['candidate'] = [
								'ido' => $interview->candidate->ido,
								'firstname' => $interview->candidate->user->firstname,
								'lastname' => $interview->candidate->user->lastname,
								'grade' => $interview->candidate->grade
							];
						}

						$recruiter['interviews'][] = $interviewToAdd;
						break;
					}
				}

				$recruiterToAdd['interviews'][] = $interviewToAdd;
			}

			$interviewByCompany['recruiters'][] = $recruiterToAdd;
		}

		return $interviewByCompany;
	}
}
