<?php

namespace CVS\Jobs;

use CVS\Company;
use CVS\Events\InvitedRecruiterWasRegistered;
use CVS\Jobs\Job;
use CVS\Recruiter;
use CVS\User;
use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class RegisterParticipantsFromRecruiterRegister extends Job implements SelfHandling, ShouldQueue
{
    use DispatchesJobs, InteractsWithQueue, SerializesModels;

    public $referral;
    public $participants = [];

    public function __construct(Recruiter $referral, array $participants)
    {
        $this->referral = $referral;
        $this->participants = $participants;
    }

    public function handle()
    {
        foreach ($this->participants as $participant) {
            if (!User::whereEmail($participant['email'])->first()) {
                \DB::transaction(function() use($participant) {
                    try {
                        $randomPassword = str_random(10);

                        // Creating the User
                        $user = User::create([
                            'email' => $participant['email'],
                            'password' => bcrypt($randomPassword),
                            'firstname' => $participant['firstname'],
                            'lastname' => $participant['lastname'],
                            'phone' => NULL,
                            'email_notifications' => true,
                            'sms_notifications' => true
                        ]);

                        // Getting or creating the Company
                        $company = $this->referral->company;

                        // Creating the Recruiter
                        $recruiter = new Recruiter;
                        $recruiter->company()->associate($company);
                        $recruiter->availability = $participant['availability'];
                        $recruiter->parking_option = (isset($participant['parkingOption']) && $participant['parkingOption']) ? true : false;
                        $recruiter->lunch_option = (isset($participant['lunchOption']) && $participant['lunchOption']) ? true : false;
                        $recruiter->save();

                        // Associating the Recruiter to the User
                        $recruiter->user()->save($user);

                        // Associating interviews to the Recruiter
                        $this->dispatch(new AddInterviewsToRecruiter($recruiter));

                        event(new InvitedRecruiterWasRegistered($this->referral, $recruiter, $randomPassword));

                        return $recruiter;
                    } catch (Exception $e) {
                        return false;
                    }
                });
            }
        }
    }
}
