<?php

namespace CVS\Jobs;

use CVS\Interview;
use CVS\Jobs\Job;
use CVS\Recruiter;
use CVS\Slot;
use Illuminate\Contracts\Bus\SelfHandling;

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
        \Log::info($this->recruiter);

        $slots = [];
        if ($this->recruiter->availability == 'all')
            $slots = Slot::all();
        else
            $slots = Slot::where('availability', $this->recruiter->availability)->get();

        foreach ($slots as $slot) {
            \Log::info('creating interview for slot');
            \Log::info($slot);

            Interview::create([
                'company_id' => $this->recruiter->company_id,
                'slot_id' => $slot->id,
                'recruiter_id' => $this->recruiter->id
            ]);
        }

    }
}
