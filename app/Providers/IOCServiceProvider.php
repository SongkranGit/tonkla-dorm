<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class IOCServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //Register for IOC Here
        $this->app->bind(
            'App\IServices\IRoomService',
            'App\Services\RoomService'
        );
        $this->app->bind(
            'App\IServices\IEquipmentService',
            'App\Services\EquipmentService'
        );    
        $this->app->bind(
            'App\IServices\IRoomEquipmentService',
            'App\Services\RoomEquipmentService'
        );
        $this->app->bind(
            'App\IServices\ISettingService',
            'App\Services\SettingService'
        );    
        $this->app->bind(
            'App\IServices\IKeycardService',
            'App\Services\KeycardService'
        );    
        $this->app->bind(
            'App\IServices\IRoomUtilityHistoryService',
            'App\Services\RoomUtilityHistoryService'
        );    
        $this->app->bind(
            'App\IServices\IRoomHistoryService',
            'App\Services\RoomHistoryService'
        );
        $this->app->bind(
            'App\IServices\IEquipmentHistoryService',
            'App\Services\EquipmentHistoryService'
        );
        $this->app->bind(
            'App\IServices\IReportService',
            'App\Services\ReportService'
        );
        $this->app->bind(
            'App\IServices\IRoomCalculationService',
            'App\Services\RoomCalculationService'
        );
        $this->app->bind(
            'App\IServices\IRoomApprovalService',
            'App\Services\RoomApprovalService'
        );
        $this->app->bind(
            'App\IServices\IApprovalPrivilegeService',
            'App\Services\ApprovalPrivilegeService'
        );

    }
}
