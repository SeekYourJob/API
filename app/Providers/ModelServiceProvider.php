<?php

namespace CVS\Providers;

use CVS\Interview;
use Illuminate\Support\ServiceProvider;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Inform candidate that the interview slot has been removed
        Interview::deleted(function($interview) {
            //TODO Inform candidate that the interview has been canceled
        });

        // Inform candidate that the interview has been canceled
        Interview::updating(function($interview) {
            $previousInterview = $interview->getOriginal();
            if (isset($previousInterview['candidate_id']) && $previousInterview['candidate_id']
                && is_null($interview->candidate_id)) {
                //TODO Inform candidate that the interview has been canceled
            }
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
