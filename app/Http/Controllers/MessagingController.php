<?php

namespace CVS\Http\Controllers;

use CVS\Candidate;
use CVS\Http\Requests\MessagingSendEmailRequest;
use CVS\Http\Requests;
use CVS\Http\Requests\MessagingSendSMSRequest;
use CVS\Mailer\CandidateMailer;
use CVS\Mailer\Mailer;
use CVS\Mailer\RecruiterMailer;
use CVS\Texter\CandidateTexter;
use CVS\Texter\RecruiterTexter;
use CVS\Texter\Texter;
use CVS\User;
use Illuminate\Http\Request;

class MessagingController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth');
	}

	public function getRemainingSMSCredits()
	{
		$this->authorize('messaging-get-remaining-sms-credits');

		return response()->json(Texter::getRemainingCredits());
	}

    public function sendEmail(MessagingSendEmailRequest $request)
    {
	    $this->authorize('messaging-send-email');

	    $mailer = new Mailer();
	    foreach($request->input('recipients') as $recipient) {
		    $mailer->sendToUser(User::whereId(app('Hashids')->decode($recipient)[0])->firstOrFail(),
			    $request->input('message.object'),
			    'emails.skeleton',
			    ['content' => nl2br($request->input('message.content'))],
			    [],
			    true
		    );
	    }

	    return response()->json('Mail sending queued');
    }

	public function sendSMS(MessagingSendSMSRequest $request)
	{
		$this->authorize('messaging-send-sms');

		$texter = new Texter();
		foreach($request->input('recipients') as $recipient)
			$texter->sendToUser(User::whereId(app('Hashids')->decode($recipient)[0])->firstOrFail(), $request->input('message'));

		return response()->json('Text message sending queued');
	}

	public function getPredefinedEmails()
	{
		$this->authorize('messaging-send-email');

		return response()->json([
			[
				'key' => 'PARKING',
				'title' => 'Recruteurs : plan d\'accès au parking + code'
			],
			[
				'key' => 'RESUME',
				'title' => 'Recruteurs : curriculums des candidats'
			],
            [
                'key' => 'PLANNING',
                'title' => 'Candidats : planning de la journée'
            ],
            [
                'key' => 'NO_INTERVIEWS_WARNING',
                'title' => 'Candidats : Pas d\'entretiens prévus'
            ]

		]);
	}

	public function getPredefinedSMS()
	{
		$this->authorize('messaging-send-sms');

		return response()->json([
			[
					'key' => 'PARKING',
					'title' => 'Recruteurs : code d\'accès au parking'
			],
			[
				'key' => 'REMINDER',
				'title' => 'Candidats : rappel du premier entretien'
			],
		]);
	}

	public function sendPredefinedEmail(Request $request)
	{
		$this->authorize('messaging-send-email');

		if ($request->has('predefinedEmailKey')) {
			switch($request->get('predefinedEmailKey')) {
				case 'PARKING':
					return (new RecruiterMailer())->sendMapAndParkingCodeToRecruiters() ? response()->json('Predefined email sent') : abort(500);
					break;
				case 'RESUME':
					return (new RecruiterMailer())->sendCandidateResumesToRecruiters() ? response()->json('Predefined email sent') : abort(500);
					break;
                case 'PLANNING':
                    return (new CandidateMailer())->sendPlanningToCandidates() ? response()->json('Predefined email sent') : abort(500);
                    break;
                case 'NO_INTERVIEWS_WARNING':
                    return (new CandidateMailer())->sendNoInterviewsWarningToCandidates() ? response()->json('Predefined email sent') : abort(500);
                    break;
				default:
					abort(422, "Predefined email not found");
			}
		}

		abort(422, "Missing predefined email key");
	}

	public function sendPredefinedSMS(Request $request)
	{
		$this->authorize('messaging-send-sms');

		if ($request->has('predefinedSMSKey')) {
			switch($request->get('predefinedSMSKey')) {
				case 'PARKING':
					return (new RecruiterTexter())->sendParkingCodeToRecruiters()  ? response()->json('Predefined SMS sent') : abort(500);
					break;
				case 'REMINDER':
					$candidates = Candidate::with(['user', 'interviews.slot', 'interviews.company'])->get();
					$candidateTexter = new CandidateTexter();
					foreach ($candidates as $candidate) {
						if (isset($candidate->interviews, $candidate->interviews[0])) {
							$interview = $candidate->interviews[0];
							$candidateTexter->sendFirstInterviewReminderToCandidate($candidate, $interview);
							unset($interview);
						}
					}
					return response()->json('Predefined SMS sent');
					break;
				default:
					abort(422, "Predefined SMS not found");
			}
		}

		abort(422, "Missing predefined SMS key");
	}
}
