<?php

namespace App\Http\Controllers\Admin\Setting;

use App\IServices\ISettingService;
use Setting;
use Redirect;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\SettingsStoreRequest;

class RoomRateDailySettingController extends Controller
{

    private $settingService;

    public function __construct(ISettingService $settingService)
    {
        $this->middleware(['auth','role:admin|owner']);
        $this->settingService = $settingService;
    } 
    
    public function getRoomRateDaily()
    {
        $roomDaily = $this->settingService->get( 'room_rate_daily', '1');
        return view('admin.settings.room_daily.show',[
            'roomDaily' => $roomDaily
        ]);
    }

    public function getRoomRateDaily2()
    {
        $roomDaily = $this->settingService->get( 'room_rate_daily_2', '1');
        return view('admin.settings.room_daily_2.show',[
            'roomDaily' => $roomDaily
        ]);
    }

    public function getRoomRateDaily3()
    {
        $roomDaily = $this->settingService->get( 'room_rate_daily_3', '1');
        return view('admin.settings.room_daily_3.show',[
            'roomDaily' => $roomDaily
        ]);
    }

    public function editRoomRateDaily()
    {
        $roomDaily = $this->settingService->get( 'room_rate_daily', '1');
        return view('admin.settings.room_daily.edit',[
            'roomDaily' => $roomDaily
        ]);
    }

    public function editRoomRateDaily2()
    {
        $roomDaily = $this->settingService->get( 'room_rate_daily_2', '1');
        return view('admin.settings.room_daily_2.edit',[
            'roomDaily' => $roomDaily
        ]);
    }

    public function editRoomRateDaily3()
    {
        $roomDaily = $this->settingService->get( 'room_rate_daily_3', '1');
        return view('admin.settings.room_daily_3.edit',[
            'roomDaily' => $roomDaily
        ]);
    }

    public function storeRoomRateDaily(SettingsStoreRequest $request)
    {
        $this->settingService->set('room_rate_daily', $request->value);
        return Redirect('admin/settings/roomratedaily');
    }

    public function storeRoomRateDaily2(SettingsStoreRequest $request)
    {
        $this->settingService->set('room_rate_daily_2', $request->value);
        return Redirect('admin/settings/roomratedaily2');
    }

    public function storeRoomRateDaily3(SettingsStoreRequest $request)
    {
        $this->settingService->set('room_rate_daily_3', $request->value);
        return Redirect('admin/settings/roomratedaily3');
    }

}
