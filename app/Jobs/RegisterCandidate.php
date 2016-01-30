<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 1/24/2016
 * Time: 7:30 PM
 */
namespace CVS\Jobs;

use CVS\Company;
use CVS\Document;
use CVS\Events\CandidateWasRegistered;
use CVS\Http\Requests\RegisterCandidateRequest;
use CVS\Jobs\Job;
use CVS\Candidate;
use CVS\User;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Log;
use Mockery\CountValidator\Exception;

class RegisterCandidate extends Job implements SelfHandling
{
    use DispatchesJobs;

    public $user;
    public $candidate;

    public function __construct($user, $candidate)
    {
        $this->user = $user;
        $this->candidate = $candidate;
    }

    public function handle()
    {
        $userInputs = $this->user;
        $candidateInputs = $this->candidate;

        // Beginning a transaction...
        $user = \DB::transaction(function() use($userInputs, $candidateInputs) {

            try {
                // Creating the User
                $user = User::create([
                    'email' => $userInputs['email'],
                    'password' => bcrypt($userInputs['password']),
                    'firstname' => $userInputs['firstname'],
                    'lastname' => $userInputs['lastname'],
                    'phone' => User::getInternationalPhoneNumber($userInputs['phone']),
                    'email_notifications' => true,
                    'sms_notifications' => true
                ]);

                if(!Candidate::getIsValidGrade($candidateInputs['grade'])){
                    throw new Exception('Invalid grade');
                }

                // Creating the Candidate
                $candidate = new Candidate;
                $candidate->grade = $candidateInputs['grade'];
                $candidate->save();

                // Associating the Candidate to the User
                $candidate->user()->save($user);

                // Associating Documents to the User
                if (isset($candidateInputs['documents']))
                    foreach ($candidateInputs['documents'] as $document)
                        $user->documents()->save(Document::find(app('Hashids')->decode($document['ido'])[0]));

                // Triggering the corresponding event
                event(new CandidateWasRegistered($candidate));

                return $user;

            } catch (\Exception $e) {
                return false;
            }

        });

        return $user;
    }
}
