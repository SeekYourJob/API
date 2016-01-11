<?php

namespace CVS;

use CVS\Traits\ObfuscatedIdTrait;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
	use ObfuscatedIdTrait;

	protected $table = 'candidates';
	protected $guarded = ['id'];
	protected $hidden = ['id'];
	protected $appends = ['ido', 'registered_slots', 'registered_slots_obfuscated', 'can_register_to_interviews'];

	public function user()
	{
		return $this->morphOne(User::class, 'profile');
	}

	public function documents()
	{
		return $this->morphMany(Document::class, 'profile');
	}

	public function interviews()
	{
		return $this->hasMany(Interview::class, 'candidate_id');
	}

	public function getRegisteredSlotsAttribute()
	{
		$slots = [];

		foreach($this->interviews as $interview)
			$slots[$interview->slot_id] = $interview->company->name;

		asort($slots);

		return ($slots);
	}

	public function getRegisteredSlotsObfuscatedAttribute()
	{
		$slots = [];

		foreach ($this->registered_slots as $slot_id => $company_name)
			$slots[app('Hashids')->encode($slot_id)] = $company_name;

		return ($slots);
	}

	public function canRegisterToInterviewsAttribute()
	{
		return $this->canRegisterToInterviews();
	}

	public function canRegisterToInterviews()
	{
		foreach ($this->user->documents as &$document)
			if ($document->status === 'ACCEPTED')
				return true;

		return false;
	}

	public static function getAllIdos()
	{
		$candidates = self::with('user')->get();

		$candidatesIdos = [];
		foreach($candidates as $candidate)
			$candidatesIdos[] = $candidate->user->ido;

		return $candidatesIdos;
	}

	public static function getIdosGroupedByGrades()
	{
		$candidates = self::with('user')->get();

		$grades = [];
		foreach($candidates as $candidate)
			$grades[$candidate->grade][] = $candidate->user->ido;

		return $grades;
	}

	public static function getIdosGroupedByEducations()
	{
		$candidates = self::with('user')->get();

		$educations = [];
		foreach($candidates as $candidate)
			if (!empty($candidate->education))
				$educations[$candidate->education][] = $candidate->user->ido;

		return $educations;
	}

	public static function getIdosGroupedByGradesAndEducations()
	{
		$candidates = self::with('user')->get();

		$gradesAndEducations = [];
		foreach($candidates as $candidate)
			if (!empty($candidate->education))
				$gradesAndEducations[$candidate->grade . ' ' . $candidate->education][] = $candidate->user->ido;

		return $gradesAndEducations;
	}
}
