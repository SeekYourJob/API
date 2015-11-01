<?php

namespace CVS\Http\Controllers;

use Auth;
use CVS\Company;
use CVS\Document;
use CVS\Interview;
use CVS\Jobs\AddInterviewsToRecruiter;
use CVS\Recruiter;
use CVS\Slot;
use Illuminate\Http\Request;

class InterviewsController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth');
		$this->middleware('organizer');
//		$this->middleware('organizer', ['except' => ['getAllForCompany', 'getAllForRecruiter']]);
	}

	public function generate()
	{
		// Removing previous Interviews
		Interview::truncate();

		$allRecruiters = Recruiter::all();
		foreach($allRecruiters as $recruiter)
			$this->dispatch(new AddInterviewsToRecruiter($recruiter));

		return Interview::all();
	}

	public function getAllForAllCompanies()
	{
		return response()->json(Interview::getAllForAllCompanies());
	}

	public function getAllForCompany(Company $company)
	{
		return response()->json(Interview::getAllForCompany($company));
	}

	public function getAllForRecruiter(Recruiter $recruiter)
	{
		if (Auth::user()->organizer || Auth::user()->id == $recruiter->user->id) {
			return response()->json(Interview::getAllForRecruiter($recruiter));
		}

		abort(401);
	}

	public function getAllSlots()
	{
		return Slot::all();
	}

	public function deleteInterview(Interview $interview)
	{
		if ($interview->delete()) {
			return response()->json('Interview deleted.');
		}

		abort(500, 'An error occured while trying to delete the interview');
	}

	public function createInterview(Request $request)
	{
		if ($request->has(['recruiter', 'slot'])) {
			$recruiter = Recruiter::findOrFail(app('Hashids')->decode($request->get('recruiter'))[0]);
			$slot = Slot::findOrFail(app('Hashids')->decode($request->get('slot'))[0]);

			$this->dispatch(new AddInterviewsToRecruiter($recruiter, $slot));
			return response()->json('Interview created.');
		}
	}

	public function freeInterview(Interview $interview)
	{
		$interview->candidate_id = null;
		$interview->save();

		return $interview;
	}
}