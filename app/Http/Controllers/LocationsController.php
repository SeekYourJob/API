<?php

namespace CVS\Http\Controllers;

use CVS\Interview;
use CVS\Location;
use CVS\Recruiter;
use Illuminate\Http\Request;

use CVS\Http\Requests;
use CVS\Http\Controllers\Controller;

class LocationsController extends Controller
{
    public function getAll()
    {
	    return response()->json(Location::all());
    }

	public function updateInterview(Interview $interview, Request $request)
	{
		if (!$request->has('idoLocation')) {
			abort(422, 'Missing new location');
		}

		if ($request->get('idoLocation') == 'NONE') {
			$interview->location_id = null;
			$interview->save();

			return response()->json($interview);
		}

		try {
			$location = Location::findByIdo($request->get('idoLocation'));
			$interview->location_id = $location->id;
			$interview->save();
		} catch (\Exception $e) {
			abort(409, 'Location already taken');
		}

		return response()->json($interview);
	}

	public function updateRecruiter(Recruiter $recruiter, Request $request)
	{
		if (!$request->get('idoLocation')) {
			abort(422, 'Missing new location');
		}

		// Removing location if asked...
		if ($request->get('idoLocation') == 'NONE') {
			foreach ($recruiter->interviews as $interview) {
				$interview->location_id = null;
				$interview->save();
			}

			return response()->json('Locations updated');
		}

		$location = Location::findByIdo($request->get('idoLocation'));
		$results = ['success' => [], 'errors' => []];
		foreach ($recruiter->interviews as $interview) {
			try {
				$interview->location_id = $location->id;
				$interview->save();
				$results['success'][] = $interview;
			} catch (\Exception $e) {
				$results['errors'][] = $interview;
			}
		}

		return response()->json($results, (count($results['errors'])) ? 409 : 200);
	}
}
