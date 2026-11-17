<?php

namespace App\Http\Controllers\Admin\Setting;

use Redirect;
use Response;
use Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExtraSettingUpdateRequest;
use App\Http\Requests\Admin\ExtraSettingStoreRequest;
use App\IServices\ISettingService;

class ExtraSettingController extends Controller
{
    //
    private $settingService;

    public function __construct(ISettingService $settingService)
    {
        $this->middleware(['auth', 'role:admin|owner']);
        $this->settingService = $settingService;
    }

    public function index(Request $request)
    {
        $extras = $this->settingService->getAllExtraWithPagination($request);
        return view('admin.settings.extra.index', [
            'extras' => $extras
        ]);
    }

    public function create()
    {
        return view('admin.settings.extra.create');
    }

    public function store(ExtraSettingStoreRequest $request)
    {
         $this->settingService->create($request, true);
        return Redirect('admin/settings/extra');
    }

    public function edit($id)
    {
        $extra = $this->settingService->getByStorageId($id);
        return view('admin.settings.extra.edit', [
            'extra' => $extra
        ]);
    }

    public function update(ExtraSettingUpdateRequest $request, $id)
    {
        $this->settingService->set($request->key, $request->value);
        return Redirect('admin/settings/extra');
    }

    public function destroy($id)
    {
        $this->settingService->delete($id);
        return Response::json();
    }
}
