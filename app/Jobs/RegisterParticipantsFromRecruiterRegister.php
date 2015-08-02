<?php

namespace CVS\Jobs;

use CVS\Jobs\Job;
use CVS\Recruiter;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class RegisterParticipantsFromRecruiterRegister extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

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
            //TODO
            Log::info('We should register participant with email ' . $participant['email']);
        }
    }
}
