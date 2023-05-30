<?php
/**
 * Created by PhpStorm.
 * User: ashraf
 * Date: 1/28/20
 * Time: 4:11 PM
 */

namespace App\Providers;

use App\Contracts\LookupContract;
use App\Contracts\MessageContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Contracts\Secdbms\Ims\ImsIncidenceContract;
use App\Contracts\Secdbms\Ims\ImsIncidenceSubTypeContract;
use App\Contracts\Secdbms\Ims\ImsInvestigationContract;
use App\Contracts\Secdbms\Ims\OtherInfoContract;
use App\Managers\LookupManager;
use App\Managers\MessageManager;
use App\Managers\Pmis\Employee\EmployeeManager;
use App\Managers\Secdbms\Ims\ImsIncidenceManager;
use App\Managers\Secdbms\Ims\ImsIncidenceSubTypeManager;
use App\Managers\Secdbms\Ims\ImsInvestigationManager;
use App\Managers\Secdbms\Ims\OtherInfoManager;
use Illuminate\Support\ServiceProvider;

class ImsContractServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LookupContract::class, LookupManager::class);
        $this->app->bind(EmployeeContract::class, EmployeeManager::class);
        $this->app->bind(MessageContract::class, MessageManager::class);
        $this->app->bind(ImsIncidenceSubTypeContract::class, ImsIncidenceSubTypeManager::class);
        $this->app->bind(ImsIncidenceContract::class, ImsIncidenceManager::class);
        $this->app->bind(ImsInvestigationContract::class, ImsInvestigationManager::class);
        $this->app->bind(OtherInfoContract::class, OtherInfoManager::class);
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
