<?php

namespace CVS\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'CVS\Events\RecruiterWasRegistered' => [
            'CVS\Listeners\EmailWelcomeEmailToRecruiter',
        ],
        'CVS\Events\InvitedRecruiterWasRegistered' => [
            'CVS\Listeners\EmailWelcomeEmailWithPasswordToInvitedRecruiter',
        ],
        'CVS\Events\InterviewWasRegistered' => [],
        'CVS\Events\InterviewWasCanceled' => [
            'CVS\Listeners\EmailCandidateInterviewWasCanceled',
        ],
        'CVS\Events\ResumeWasRefused' => [
            'CVS\Listeners\EmailCandidateResumeWasRefused',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
