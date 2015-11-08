<?php

namespace CVS;

use CVS\Traits\ObfuscatedIdTrait;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
	use ObfuscatedIdTrait;

	protected $table = 'slots';
	protected $guarded = ['id'];
	protected $hidden = ['id', 'created_at', 'updated_at', 'availability'];
	protected $appends = ['ido', 'begins_at_formatted', 'ends_at_formatted'];

	public function interviews()
	{
		return $this->hasMany(Interview::class);
	}

	public function getBeginsAtFormattedAttribute()
	{
		return date("H:i", strtotime($this->begins_at));
	}

	public function getEndsAtFormattedAttribute()
	{
		return date("H:i", strtotime($this->ends_at));
	}

	public function getAvailableCandidatesForSlot()
	{
		$slot = $this;

		$availableCandidates = [];
		$candidates = Candidate::with(['interviews', 'user'])->get();

		foreach ($candidates as $candidate) {
			$candidateIsAvailable = true;

			foreach ($candidate->interviews as $interview) {
				if ($interview->slot_id == $this->id) {
					$candidateIsAvailable = false;
					break;
				}
			}

			if ($candidateIsAvailable)
				$availableCandidates[] = $candidate;
		}


		return ['slots' => self::all(), 'candidates' => $availableCandidates];
	}
}
