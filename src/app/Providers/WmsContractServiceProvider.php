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
use App\Contracts\Secdbms\Watchman\AgencyContract;
use App\Contracts\Secdbms\Watchman\BasicInfoContract;
use App\Contracts\Secdbms\Watchman\BookConfirmedEmailContract;
use App\Contracts\Secdbms\Watchman\BookConfirmedMessageContract;
use App\Contracts\Secdbms\Watchman\BookingContract;
use App\Contracts\Secdbms\Watchman\DutyAcknowledgementContract;
use App\Contracts\Secdbms\Watchman\InvoiceContract;
use App\Contracts\Secdbms\Watchman\WmNotifyMessageContract;
use App\Managers\LookupManager;
use App\Managers\MessageManager;
use App\Managers\Pmis\Employee\EmployeeManager;
use App\Managers\Secdbms\Watchman\AgencyManager;
use App\Managers\Secdbms\Watchman\BasicInfoManager;
use App\Managers\Secdbms\Watchman\BookConfirmedEmailManager;
use App\Managers\Secdbms\Watchman\BookConfirmedMessageManager;
use App\Managers\Secdbms\Watchman\BookingManager;
use App\Managers\Secdbms\Watchman\DutyAcknowledgementManager;
use App\Managers\Secdbms\Watchman\InvoiceManager;
use App\Managers\Secdbms\Watchman\WmNotifyMessageManager;
use Illuminate\Support\ServiceProvider;

class WmsContractServiceProvider extends ServiceProvider
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
        $this->app->bind(BasicInfoContract::class, BasicInfoManager::class);
        $this->app->bind(BookingContract::class, BookingManager::class);
        $this->app->bind(DutyAcknowledgementContract::class, DutyAcknowledgementManager::class);
        $this->app->bind(BookConfirmedMessageContract::class, BookConfirmedMessageManager::class);
        $this->app->bind(WmNotifyMessageContract::class, WmNotifyMessageManager::class);
        $this->app->bind(BookConfirmedEmailContract::class, BookConfirmedEmailManager::class);
        $this->app->bind(InvoiceContract::class, InvoiceManager::class);
        $this->app->bind(AgencyContract::class, AgencyManager::class);
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
