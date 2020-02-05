<?php

namespace App\Providers;
use Route;
use Laravel\Passport\Passport; 
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\UrgentReportPolicy;
use App\Policies\ComplainPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::resource('urgent_reports', UrgentReportPolicy::class);
        Gate::define('urgent_reports.show', UrgentReportPolicy::class . '@show');
        Gate::resource('complains', ComplainPolicy::class);
        Gate::define('complains.show', ComplainPolicy::class . '@show');
        // Passport::routes();
        Route::group([ 'middleware' => 'cors'], function() {
            Passport::routes();
        });
        //
    }
}
