<?php

namespace CVS\Http\Controllers;

use Auth;
use CVS\Company;
use CVS\Document;
use CVS\Interview;
use CVS\Jobs\AddInterviewsToRecruiter;
use CVS\Recruiter;

class InterviewsController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth');
		$this->middleware('organizer');
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
}