<?php

namespace CVS\Http\Controllers;

use CVS\Candidate;
use CVS\Company;
use Illuminate\Http\Request;
use CVS\Http\Requests;
use CVS\Http\Controllers\Controller;

class CandidatesController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth');
	}

    public function index()
    {
        return Candidate::with(['user'])->get();
    }

    public function getInterviewsForCandidate(Candidate $candidate)
    {
		return Company::getInterviewsGroupedByCompaniesForCandidate($candidate);
    }
}
