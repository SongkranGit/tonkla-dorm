<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsStoreRequest;
use App\IServices\ISettingService;
use Redirect;
use Setting;

class VATSettingController extends Controller
{
    private $settingService;
    
    public function __construct(ISettingService $settingService)
    {
        $this->middleware(['auth','role:admin|owner']);
        $this->settingService = $settingService;
    }
 
    public function getVAT()
    {
        $vat = $this->settingService->get( 'vat', '1');
        return view('admin.settings.vat.show',[
            'vat' => $vat
        ]);
    }

    public function editVAT()
    {
        $vat = $this->settingService->get( 'vat', '1');
        return view('admin.settings.vat.edit',[
            'vat' => $vat
        ]);
    }

    public function storeVAT(SettingsStoreRequest $request)
    {
        $this->settingService->set('vat', $request->value);
        return Redirect('admin/settings/vat/edit');
    }

    public function getVATSpecial()
    {
        $vat = $this->settingService->get( 'vat_special', '1');
        return view('admin.settings.vat_special.show',[
            'vat' => $vat
        ]);
    }

    public function editVATSpecial()
    {
        $vat = $this->settingService->get( 'vat_special', '1');
        return view('admin.settings.vat_special.edit',[
            'vat' => $vat
        ]);
    }

    public function storeVATSpecial(SettingsStoreRequest $request)
    {
        $this->settingService->set('vat_special', $request->value);
        return Redirect('admin/settings/vatspecial');
    }

    public function getVATSpecialProperty()
    {
        $vat = $this->settingService->get( 'vat_special_property', '1');
        return view('admin.settings.vat_special_property.show',[
            'vat' => $vat
        ]);
    }

    public function editVATSpecialProperty()
    {
        $vat = $this->settingService->get( 'vat_special_property', '1');
        return view('admin.settings.vat_special_property.edit',[
            'vat' => $vat
        ]);
    }

    public function storeVATSpecialProperty(SettingsStoreRequest $request)
    {
        $this->settingService->set('vat_special_property', $request->value);
        return Redirect('admin/settings/vatspecialproperty');
    }
}
