<?php

namespace CVS\Jobs;

use CVS\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteParticipantsFromRecruiterRegister extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $emails = [];

    public function __construct(array $emails)
    {
        $this->emails = $emails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->emails as $email) {
            //TODO
            \Log::info('We should send an email to ' . $email . ' to invite him!');
        }
    }
}
