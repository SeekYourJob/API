<?php

namespace CVS\Jobs;

use CVS\Jobs\Job;
use CVS\Mailer\UserMailer;
use CVS\Recruiter;
use CVS\User;
use CVS\WaitingList;
use DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteParticipantsFromRecruiterRegister extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $referral;
    public $emails = [];

    public function __construct(Recruiter $referral, array $emails)
    {
        $this->referral = $referral;
        $this->emails = $emails;
    }

    public function handle()
    {
        $userMailer = new UserMailer;

        foreach($this->emails as $email) {
            WaitingList::updateOrCreate(['email' => $email], ['sent' => true]);
            $userMailer->inviteParticipant($this->referral, $email);
        }
    }
}
