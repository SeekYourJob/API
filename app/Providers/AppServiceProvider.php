<?php

namespace CVS\Providers;

use Illuminate\Support\ServiceProvider;
use libphonenumber\PhoneNumberUtil;
use Vinkla\Hashids\Facades\Hashids;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->singleton('Optimus', function($app) {
//            return new Optimus(env('OPTIMUS_PRIME', 982591787), env('OPTIMUS_INVERSE', 604130691), env('OPTIMUS_RANDOM', 383484086));
//        });

        $this->app->singleton('Hashids', function($app) {
           return Hashids::connection();
        });

        $this->app->singleton('PhoneUtils', function($app) {
            return PhoneNumberUtil::getInstance();
        });
    }
}
