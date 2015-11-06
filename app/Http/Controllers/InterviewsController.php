<?php

namespace CVS\Http\Controllers;

use Auth;
use CVS\Candidate;
use CVS\Company;
use CVS\Document;
use CVS\Events\InterviewWasCanceled;
use CVS\Events\InterviewWasRegistered;
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
	}

	public function generate()
	{
		$this->authorize('interviews-required-organizer');

		// Removing previous Interviews
		Interview::truncate();

		$allRecruiters = Recruiter::all();
		foreach($allRecruiters as $recruiter)
			$this->dispatch(new AddInterviewsToRecruiter($recruiter));

		return Interview::all();
	}

	public function getAll()
	{
		$this->authorize('interviews-required-organizer');

		return response()->json(Interview::getAllForAllCompanies());
	}

	public function getAllForCompany(Company $company)
	{
		$this->authorize('interviews-required-organizer');

		return response()->json(Interview::getAllForCompany($company));
	}

	public function getAllForRecruiter(Recruiter $recruiter)
	{
		if (Auth::user()->organizer || Auth::user()->id == $recruiter->user->id) {
			return response()->json(Interview::getAllForRecruiter($recruiter));
		}

		abort(401);
	}

	public function getAllForCandidate(Candidate $candidate)
	{
		return Company::getInterviewsGroupedByCompaniesForCandidate($candidate);
	}

	public function getAllSlots()
	{
		$this->authorize('interviews-required-organizer');

		return Slot::all();
	}

	public function deleteInterview(Interview $interview)
	{
		$this->authorize('interviews-required-organizer');

		if ($interview->delete()) {
			return response()->json('Interview deleted.');
		}

		abort(500, 'An error occured while trying to delete the interview');
	}

	public function createInterview(Request $request)
	{
		$this->authorize('interviews-required-organizer');

		if ($request->has(['recruiter', 'slot'])) {
			$recruiter = Recruiter::findOrFail(app('Hashids')->decode($request->get('recruiter'))[0]);
			$slot = Slot::findOrFail(app('Hashids')->decode($request->get('slot'))[0]);

			$this->dispatch(new AddInterviewsToRecruiter($recruiter, $slot));
			return response()->json('Interview created.');
		}
	}

	public function register(Request $request)
	{
		$this->authorize('interviews-can-register');

		$candidate = Auth::user()->profile;
		$company = Company::whereId(app('Hashids')->decode($request->get('company_ido'))[0])->firstOrFail();

		foreach ($candidate->interviews as $interview) {
			// Checking if the Candidate already has an Interview for the given Slot
			if ($interview->slot_id == $request->get('slot_id'))
				return response()->json(['error' => 'slot_already_registered'], 422);

			// Checking if the Candidate already has an Interview with the giver Company
			if ($interview->company_id == $company->id) {
				$interview->candidate_id = null;
				$interview->save();
			}
		}

		$freeSlotFoundAndInterviewRegistered = false;
		foreach ($company->recruiters as $recruiter) {
			foreach ($recruiter->interviews as $interview) {
				if ($interview->slot_id == $request->get('slot_id') && is_null($interview->candidate_id) && !$freeSlotFoundAndInterviewRegistered) {
					$interview->candidate_id = $candidate->id;
					$interview->save();

					$freeSlotFoundAndInterviewRegistered = true;
					break;
				}
			}

			if ($freeSlotFoundAndInterviewRegistered)
				break;
		}

		event(new InterviewWasRegistered($interview));

		return response()->json(['status' => $interview]);
	}

	public function freeInterview(Interview $interview)
	{
		$this->authorize('interviews-can-cancel', [$interview]);

		$interview->candidate_id = null;
		$interview->save();

		event(new InterviewWasCanceled($interview));

		return response()->json('Interview canceled');
	}
}