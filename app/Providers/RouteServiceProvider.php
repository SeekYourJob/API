<?php

namespace CVS\Providers;

use CVS\Company;
use CVS\Recruiter;
use CVS\User;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'CVS\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        $router->bind('user', function($value, $route) {
            return User::findOrFail(app('Optimus')->decode($value));
        });

        $router->bind('companies', function($value, $route) {
            return Company::findOrFail(app('Optimus')->decode($value));
        });

        $router->bind('recruiters', function($value, $route) {
            return Recruiter::findOrFail(app('Optimus')->decode($value));
        });

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
