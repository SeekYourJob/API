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
        'CVS\Events\InterviewWasRegistered' => [
            'CVS\Listeners\EmailCandidateInterviewWasRegistered',
            'CVS\Listeners\TextCandidateInterviewWasRegistered'
        ],
        'CVS\Events\InterviewWasCanceled' => [
            'CVS\Listeners\EmailCandidateInterviewWasCanceled',
            'CVS\Listeners\TextCandidateInterviewWasCanceled',
        ],
        'CVS\Events\ResumeWasRefused' => [
            'CVS\Listeners\EmailCandidateResumeWasRefused',
        ],
        'CVS\Events\ResumeWasAccepted' => [
            'CVS\Listeners\EmailCandidateResumeWasAccepted',
        ],
        'CVS\Events\InterviewStatusWasUpdated' => [],
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
