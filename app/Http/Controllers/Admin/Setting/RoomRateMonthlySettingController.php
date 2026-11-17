<?php

namespace App\Http\Controllers\Admin\Setting;

use App\IServices\ISettingService;
use Setting;
use Redirect;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\SettingsStoreRequest;

class RoomRateMonthlySettingController extends Controller
{
    private $settingService;
    
    public function __construct(ISettingService $settingService)
    {
        $this->middleware(['auth','role:admin|owner']);
        $this->settingService = $settingService;
    } 
 
    public function getRoomRateMonthlyFan()
    {
        $roomMonthly = $this->settingService->get('room_rate_monthly_fan', '1');
        return view('admin.settings.room_monthly_fan.show',[
            'roomMonthly' => $roomMonthly
        ]);
    }

    public function editRoomRateMonthlyFan()
    {
        $roomMonthly = $this->settingService->get('room_rate_monthly_fan', '1');
        return view('admin.settings.room_monthly_fan.edit',[
            'roomMonthly' => $roomMonthly
        ]);
    }

    public function storeRoomRateMonthlyFan(SettingsStoreRequest $request)
    {
        $this->settingService->set('room_rate_monthly_fan', $request->value);
        return Redirect('admin/settings/roomratemonthlyfan');
    }
 
    public function getRoomRateMonthlyFan2()
    {
        $roomMonthly = $this->settingService->get('room_rate_monthly_fan_2', '1');
        return view('admin.settings.room_monthly_fan_2.show',[
            'roomMonthly' => $roomMonthly
        ]);
    }

    public function editRoomRateMonthlyFan2()
    {
        $roomMonthly = $this->settingService->get('room_rate_monthly_fan_2', '1');
        return view('admin.settings.room_monthly_fan_2.edit',[
            'roomMonthly' => $roomMonthly
        ]);
    }

    public function storeRoomRateMonthlyFan2(SettingsStoreRequest $request)
    { 
        $this->settingService->set('room_rate_monthly_fan_2', $request->value);
        return Redirect('admin/settings/roomratemonthlyfan2');
    }
 
    public function getRoomRateMonthlyAir()
    {
        $roomMonthly = $this->settingService->get('room_rate_monthly_air', '1');
        return view('admin.settings.room_monthly_air.show',[
            'roomMonthly' => $roomMonthly
        ]);
    }

    public function editRoomRateMonthlyAir()
    {
        $roomMonthly = $this->settingService->get( 'room_rate_monthly_air', '1');
        return view('admin.settings.room_monthly_air.edit',[
            'roomMonthly' => $roomMonthly
        ]);
    }

    public function storeRoomRateMonthlyAir(SettingsStoreRequest $request)
    {
        $this->settingService->set('room_rate_monthly_air', $request->value);
        return Redirect('admin/settings/roomratemonthlyair');
    }
 
    public function getRoomRateMonthlyAir2()
    {
        $roomMonthly = $this->settingService->get('room_rate_monthly_air_2', '1');
        return view('admin.settings.room_monthly_air_2.show',[
            'roomMonthly' => $roomMonthly
        ]);
    }

    public function editRoomRateMonthlyAir2()
    {
        $roomMonthly = $this->settingService->get('room_rate_monthly_air_2', '1');
        return view('admin.settings.room_monthly_air_2.edit',[
            'roomMonthly' => $roomMonthly
        ]);
    }

    public function storeRoomRateMonthlyAir2(SettingsStoreRequest $request)
    {
        $this->settingService->set('room_rate_monthly_air_2', $request->value);
        return Redirect('admin/settings/roomratemonthlyair2');
    }
 
    public function getRoomRateMonthlyHouse()
    {
        $roomMonthly = $this->settingService->get('room_rate_monthly_house', '1');
        return view('admin.settings.room_monthly_house.show',[
            'roomMonthly' => $roomMonthly
        ]);
    }

    public function editRoomRateMonthlyHouse()
    {
        $roomMonthly = $this->settingService->get('room_rate_monthly_house', '1');
        return view('admin.settings.room_monthly_house.edit',[
            'roomMonthly' => $roomMonthly
        ]);
    }

    public function storeRoomRateMonthlyHouse(SettingsStoreRequest $request)
    {
        $this->settingService->set('room_rate_monthly_house', $request->value);
        return Redirect('admin/settings/roomratemonthlyhouse');
    }
}
