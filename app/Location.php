<?php

namespace CVS;

use CVS\Traits\ObfuscatedIdTrait;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	use ObfuscatedIdTrait;

	protected $table = 'locations';
	protected $guarded = ['id'];
	protected $hidden = ['id', 'created_at', 'updated_at'];
	protected $appends = ['ido'];

	public static function findByIdo($ido)
	{
		return self::find(app('Hashids')->decode($ido)[0]);
	}

	public static function getBookings()
	{
		$allLocations = self::all();
		$allSlots = Slot::all();

		foreach ($allLocations as &$location) {
			$bookings = [];
			foreach ($allSlots as &$slot) {

				$intervw = false;
				foreach ($slot->interviews as &$interview) {
					if ($interview->location_id === $location->id) {
						$intervw = [
							'recruiter' => [
								'ido' => $interview->recruiter->ido,
								'firstname' => $interview->recruiter->user->firstname,
								'lastname' => $interview->recruiter->user->lastname,
								'company' => $interview->recruiter->company->name
							],
							'candidate' => [
								'ido' => $interview->candidate->ido,
								'firstname' => $interview->candidate->user->firstname,
								'lastname' => $interview->candidate->user->lastname,
							]
						];
						break;
					}
				}

				$bookings[] = [
					'begins_at' => $slot->begins_at_formatted,
					'interview' => $intervw
				];
			}

			$location['bookings'] = $bookings;
		}

		return $allLocations;
	}
}
