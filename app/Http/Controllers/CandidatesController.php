<?php

namespace CVS\Http\Controllers;

use Auth;
use CVS\Candidate;
use CVS\Company;
use CVS\Slot;
use Illuminate\Http\Request;
use CVS\Http\Requests;
use CVS\Http\Controllers\Controller;

class CandidatesController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth');
        $this->middleware('organizer', ['except' => ['show']]);
	}

    public function index()
    {
        return Candidate::with(['user'])->get();
    }

    public function show(Candidate $candidate)
    {
        if (Auth::user()->organizer || Auth::user()->id === $candidate->user->id) {
            return $candidate::with(['user','interviews'])
                ->whereId($candidate->id)
                ->first();
        }

        abort(401);
    }

    public function getInterviewsForCandidate(Candidate $candidate)
    {
		return Company::getInterviewsGroupedByCompaniesForCandidate($candidate);
    }

	public function showSummary(Candidate $candidate)
	{
		$summary = [];
		$interviewsCount = 0;

		foreach (Slot::all() as &$slot) {
			$newSummary = ['slot' => $slot, 'interview' => false];
			foreach ($candidate->interviews as &$interview) {
				if ($slot->id === $interview->slot->id) {
					$newSummary['interview'] = [
						'ido' => $interview->ido,
						'company' => $interview->company
					];
					$interviewsCount++;

					break;
				}
			}

			$summary[] = $newSummary;
		}

		return response()->json(['count' => $interviewsCount, 'summary' => $summary]);
	}

    public function update(Request $request, Candidate $candidate)
    {
        if (Auth::user()->organizer) {
            try {
                // Updating user
                $candidate->user->update([
                    'email' => $request->input('user.email'),
                    'firstname' => $request->input('user.firstname'),
                    'lastname' => $request->input('user.lastname'),
                    'phone' => $request->input('user.phone'),
                ]);

                // Updating candidate
                $candidate->update([
                    'grade' => $request->input('grade')
                ]);

                return $candidate;
            } catch (Exception $e) {
                abort(500);
            }
        }

        abort(401);
    }
}
