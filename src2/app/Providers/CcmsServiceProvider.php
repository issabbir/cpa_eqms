<?php

namespace App\Providers;

use App\Contracts\Ccms\EquipmentListContract;
use App\Managers\Ccms\EcLisManager;
use Illuminate\Support\ServiceProvider;

class CcmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EquipmentListContract::class, EcLisManager::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
