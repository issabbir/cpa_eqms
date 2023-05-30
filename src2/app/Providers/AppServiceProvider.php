<?php

namespace App\Providers;

use App\Contracts\EmailTransportContract;
use App\Contracts\ForgotPasswordEmailContract;
use App\Managers\EmailTransportManager;
use App\Managers\ForgotPasswordEmailManager;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;


class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(EmailTransportContract::class, EmailTransportManager::class);
        // FIXME: FORGOT PASSWORD. NEEDED.
        $this->app->bind(ForgotPasswordEmailContract::class, ForgotPasswordEmailManager::class);
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('do-everything', function ($user) {
            return $user->hasPermission('do-everything');
        });
    }
}
