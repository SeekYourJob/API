<?php

namespace CVS\Jobs;

use CVS\Company;
use CVS\Document;
use CVS\Events\RecruiterWasRegistered;
use CVS\Http\Requests\RegisterRecruiterRequest;
use CVS\Jobs\Job;
use CVS\Recruiter;
use CVS\User;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Log;

class RegisterRecruiter extends Job implements SelfHandling
{
    use DispatchesJobs;

    public $user;
    public $recruiter;
    public $participantsEmails;
    public $participantsData;

    public function __construct($user, $recruiter, $participantsEmails, $participantsData)
    {
        $this->user = $user;
        $this->recruiter = $recruiter;
        $this->participantsEmails = $participantsEmails;
        $this->participantsData = $participantsData;
    }

    public function handle()
    {
        $userInputs = $this->user;
        $recruiterInputs = $this->recruiter;
        $participantsEmails = $this->participantsEmails;
        $participantsData = $this->participantsData;

        // Beginning a transaction...
        $user = \DB::transaction(function() use($userInputs, $recruiterInputs, $participantsEmails, $participantsData) {

            try {
                // Creating the User
                $user = User::create([
                    'email' => $userInputs['email'],
                    'password' => bcrypt($userInputs['password']),
                    'firstname' => $userInputs['firstname'],
                    'lastname' => $userInputs['lastname'],
                    'phone' => User::getInternationalPhoneNumber($userInputs['phone']),
                    'email_notifications' => true,
                    'sms_notifications' => false
                ]);

                // Getting or creating the Company
                $company = Company::firstOrCreate([
                    'name' => $recruiterInputs['company']['name'],
                    'job_types' => $recruiterInputs['company']['jobTypes']
               ]);

                // Creating the Recruiter
                $recruiter = new Recruiter;
                $recruiter->company()->associate($company);
                $recruiter->availability = $recruiterInputs['availability'];
                $recruiter->parking_option = isset($recruiterInputs['parkingOption']) ? true : false;
                $recruiter->lunch_option = isset($recruiterInputs['lunchOption']) ? true : false;
                $recruiter->save();

                // Associating the Recruiter to the User
                $recruiter->user()->save($user);

                // Associating interviews to the Recruiter
                $this->dispatch(new AddInterviewsToRecruiter($recruiter));

                // Associating Documents to the User
                if (isset($recruiterInputs['documents']))
                    foreach ($recruiterInputs['documents'] as $document)
                        $user->documents()->save(Document::find(app('Hashids')->decode($document['id'])[0]));

                // Inviting other participants by email
                $this->dispatch(new InviteParticipantsFromRecruiterRegister($recruiter, $participantsEmails));

                // Registering other participants with provided data
                $this->dispatch(new RegisterParticipantsFromRecruiterRegister($recruiter, $participantsData));

                // Triggering the corresponding event
                event(new RecruiterWasRegistered($recruiter));

                return $user;

            } catch (\Exception $e) {
                return false;
            }

        });

        return $user;
    }
}
