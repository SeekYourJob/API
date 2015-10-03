<?php

namespace CVS\Jobs;

use CVS\Interview;
use CVS\Jobs\Job;
use CVS\Recruiter;
use CVS\Slot;
use Illuminate\Contracts\Bus\SelfHandling;
use Log;

class AddInterviewsToRecruiter extends Job implements SelfHandling
{
    public $recruiter;

    public function __construct(Recruiter $recruiter)
    {
        $this->recruiter = $recruiter;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $slots = [];
        if ($this->recruiter->availability == 'ALL')
            $slots = Slot::all();
        else
            $slots = Slot::where('availability', $this->recruiter->availability)->get();

        foreach ($slots as $slot) {
            Log::info('creating interview for slot');
            Log::info($slot);

            Interview::create([
                'company_id' => $this->recruiter->company_id,
                'slot_id' => $slot->id,
                'recruiter_id' => $this->recruiter->id
            ]);
        }

    }
}
