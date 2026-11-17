<?php

namespace App\Http\Controllers\Admin\Setting;

use App\IServices\ISettingService;
use Setting;
use Redirect;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\SettingsStoreRequest;

class WaterSettingController extends Controller
{
    private $settingService;
    
    public function __construct(ISettingService $settingService)
    {
        $this->middleware(['auth','role:admin|owner']);
        $this->settingService = $settingService;
    }
 
    public function getWater()
    {
        $waterUnit = $this->settingService->get('water_price_per_unit', '1');
        return view('admin.settings.water.show',[
            'waterUnit' => $waterUnit
        ]);
    }

    public function editWater()
    {
        $waterUnit = $this->settingService->get('water_price_per_unit', '1');
        return view('admin.settings.water.edit',[
            'waterUnit' => $waterUnit
        ]);
    }

    public function storeWater(SettingsStoreRequest $request)
    {
        $this->settingService->set('water_price_per_unit', $request->value);
        return Redirect('admin/settings/water');
    }

}
