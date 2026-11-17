<?php

namespace App\Http\Controllers\Admin\Setting;

use App\IServices\ISettingService;
use App\Models\AppSetting;
use Setting;
use Redirect;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\SettingsStoreRequest;

class ElectricSettingController extends Controller
{
    private $settingService;

    public function __construct(ISettingService $settingService)
    {
        $this->middleware(['auth','role:admin|owner']);
        $this->settingService = $settingService;
    }

    public function getElectric()
    {
        $electricUnit = $this->settingService->get( 'electrict_price_per_unit', '3');
        return view('admin.settings.electric.show',[
            'electricUnit' => $electricUnit
        ]);
    }
    
    public function editElectric()
    {
        $electricUnit = $this->settingService->get( 'electrict_price_per_unit', '1');
        return view('admin.settings.electric.edit',[
            'electricUnit' => $electricUnit
        ]);
    }
    
    public function storeElectric(SettingsStoreRequest $request)
    {
        $this->settingService->set('electrict_price_per_unit', $request->value);
        return Redirect('admin/settings/electric');
    }
    
}
