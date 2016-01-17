<?php

namespace CVS;

use CVS\Enums\InterviewStatus;
use CVS\Events\InterviewWasCanceled;
use CVS\Events\InterviewWasRegistered;
use CVS\Traits\ObfuscatedIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Interview extends Model
{
	use ObfuscatedIdTrait;

	protected $table = 'interviews';
	protected $guarded = ['id'];
	protected $hidden = ['id', 'created_at', 'updated_at', 'slot_id', 'company_id', 'recruiter_id', 'candidate_id', 'location_id'];
	protected $appends = ['ido', 'slot_ido'];

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

	public function location()
	{
		return $this->belongsTo(Location::class);
	}

	public function getSlotIdoAttribute()
	{
		return app('Hashids')->encode($this->slot_id);
	}

	public static function getAllForAllCompanies()
	{
		$allSlots = Slot::all();
		$allCompanies = Company::with(['recruiters.interviews.candidate.user', 'recruiters.user'])
			->orderBy('name', 'ASC')
			->get();

		$allInterviewsByCompany = [];
		foreach ($allCompanies as $company) {
			$allInterviewsByCompany[] = self::getAllForCompany($company, $allSlots);
		}

		return $allInterviewsByCompany;
	}

	public static function getAllForCompany(Company $company, &$allSlots = false)
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
			$interviewByCompany['recruiters'][] = self::getAllForRecruiter($recruiter, $allSlots);
		}

		return $interviewByCompany;
	}

	public static function getAllForRecruiter(Recruiter $recruiter, &$allSlots = false)
	{
		if ( ! $allSlots) {
			$allSlots = Slot::all();
		}

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
				'location' => false,
				'status' => InterviewStatus::UNAVAILABLE
			];

			foreach ($recruiter->interviews as $interview) {
				// Add the location
				$interviewToAdd['location'] = (!is_null($interview->location)) ? $interview->location : false;

				// Check if the recruiter is available for the specified slot
				if (isset($slot->id, $interview->slot_id) && $slot->id == $interview->slot_id) {
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

		return $recruiterToAdd;
	}

	public static function register(Company $company, Slot $slot, Candidate $candidate, Recruiter $recruiter = null, &$error = false)
	{
		$interview = false;

		foreach ($candidate->interviews as $interview) {
			// Checking if the Candidate already has an Interview for the given Slot
			if ($interview->slot_id == $slot->id) {
				$error = 'slot_already_registered';
				return false;
			}

			// Checking if the Candidate already has an Interview with the giver Company
			if ($interview->company_id == $company->id) {
				$interview->candidate_id = null;
				$interview->save();
			}
		}

		$recruiters = [];
		if (!is_null($recruiter)) {
			$recruiters[] = $recruiter;
		} else {
			$recruiters = $company->recruiters;
		}

		$freeSlotFoundAndInterviewRegistered = false;
		foreach ($recruiters as $recruiter) {
			foreach ($recruiter->interviews as $interview) {
				if ($interview->slot_id == $slot->id && is_null($interview->candidate_id) && !$freeSlotFoundAndInterviewRegistered) {
					$interview->candidate_id = $candidate->id;
					$interview->save();

					$freeSlotFoundAndInterviewRegistered = true;
					break;
				}
			}

			if ($freeSlotFoundAndInterviewRegistered) {
				break;
			}
		}

		event(new InterviewWasRegistered($interview));

		return $interview;
	}

	public function free()
	{
		$this->candidate_id = null;
		$this->save();

		event(new InterviewWasCanceled($this));
	}
}
