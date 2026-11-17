<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BranchHelper;
use App\Helpers\RoomServiceHelper;
use App\Http\Controllers\Controller;
use App\IServices\IRoomService;
use App\Models\AppSettingEloquentStorage;
use App\Models\RoomUtilityHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Models\Activity;

use Response;
use Redirect;

use App\Models\Room;
use App\Models\RoomStayHistory;

use App\Http\Requests\Admin\Room\RoomHistoryUpdateRequest;

use App\IServices\ISettingService;
use App\IServices\IRoomHistoryService;
use App\IServices\IRoomCalculationService;

class RoomHistoryController extends Controller
{
    private $settingService;
    private $roomHistoryService;
    private $roomCalculationService;

    public function __construct(
        IRoomService $roomService,
        IRoomHistoryService $roomHistoryService,
        ISettingService $settingService,
        IRoomCalculationService $roomCalculationService
    )
    {
        $this->middleware(['auth', 'role:admin|owner']);
        $this->roomService = $roomService;
        $this->settingService = $settingService;
        $this->roomHistoryService = $roomHistoryService;
        $this->roomCalculationService = $roomCalculationService;
    }

    public function index(Request $request)
    {
        $roomId = $request->get('RoomId');
        if ($roomId != null) {
            $room = Room::findOrFail($roomId);
            $roomHistories = $this->roomHistoryService->getAllWithPagination($request);
            return view('admin.room_history.index', [
                'roomHistories' => $roomHistories,
                'room' => $room,
            ]);
        }

        return Redirect('room');
    }

    public function edit($id)
    {
        $roomHistory = Activity::findOrFail($id);
        $currentRoomHistory = json_decode($roomHistory->properties);

        $roomRateDaily = $this->settingService->get('room_rate_daily', 0);
        $roomRateDaily2 = $this->settingService->get('room_rate_daily_2', 0);
        $roomRateDaily3 = $this->settingService->get('room_rate_daily_3', 0);
        $furniture = $this->settingService->get('extra_2', 0);

        return view('admin.room_history.edit', [
            'room' => $currentRoomHistory->room,
            'utilities' => $this->getRoomUtilities(),
            'currentRoomHistory' => $currentRoomHistory,
            'roomHistory' => $roomHistory,
            'currentCheckedInRoomStayHistroy' => $currentRoomHistory->currentCheckedInRoomStayHistroy,
            'roomRateDaily' => $roomRateDaily,
            'roomRateDaily2' => $roomRateDaily2,
            'roomRateDaily3' => $roomRateDaily3,
            'furniture' => $furniture
        ]);
    }

    public function update(RoomHistoryUpdateRequest $request, $id)
    {
        $roomHistory = Activity::findOrFail($id, ['subject_id', 'subject_type', 'properties']);
        $currentRoomHistory = json_decode($roomHistory->properties);
        if ($roomHistory->subject_type == 'App\Models\Room') {
            $roomId = $currentRoomHistory->room->id;
            $this->roomService->updateRoom($request, $roomId);
            return Redirect::back();
        }
        abort(404);
    }

    public function destroy($id)
    {
        $roomHistory = Activity::findOrFail($id);
        $roomHistory->delete();
        return Response::json();
    }

    public function showPrint(Request $request, $id)
    {
        $printType = "print";
        $lang = $request->get('lang');
        $roomHistory = Activity::findOrFail($id);
        $currentRoomHistory = json_decode($roomHistory->properties);
        $currentCheckedInRoomStayHistory = $this->buildCurrentCheckedInRoomStayHistory($currentRoomHistory);
        $view = $this->getPrintViewPage($printType);
        if (BranchHelper::getCurrentBranchId() == Config::get('constants.branches.hotel')) {
            $totalPayTax = $this->roomCalculationService->calculateTaxHotel($currentCheckedInRoomStayHistory);
            $result = $this->roomCalculationService->calculateTotalForHotelBranch($currentCheckedInRoomStayHistory);
        } else {
            $totalPayTax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistory, $this->settingService);
            $result = $this->roomCalculationService->calculateTotal($currentCheckedInRoomStayHistory);
        }

        return view($view, [
            'lang' => $lang != null ? $lang : 'th',
            'printAction' => Config::get('constants.print'),
            'totalPayTax' => $totalPayTax,
            'sumTotal' => $result['calculatedTotal'],
            'settingService' => $this->settingService,
            'currentCheckedInRoomStayHistroy' => $currentCheckedInRoomStayHistory
        ]);
    }

    public function showPrintCheckin(Request $request, $id)
    {
        $printType = "checkin";
        $lang = $request->get('lang');
        $roomHistory = Activity::findOrFail($id);
        $currentRoomHistory = json_decode($roomHistory->properties);
        $currentCheckedInRoomStayHistory = $this->buildCurrentCheckedInRoomStayHistory($currentRoomHistory);
        $view = $this->getPrintViewPage($printType);
        $result = $this->roomCalculationService->calculatePrintCheckin($currentCheckedInRoomStayHistory);
        $totalPayTax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistory, $this->settingService);

        return view($view, [
            'lang' => $lang != null ? $lang : 'th',
            'printAction' => Config::get('constants.print_checkin'),
            'totalPayTax' => $totalPayTax,
            'sumTotal' => $result['calculatedTotal'],
            'settingService' => $this->settingService,
            'currentCheckedInRoomStayHistroy' => $currentCheckedInRoomStayHistory
        ]);
    }

    public function showPrintCheckout(Request $request, $id)
    {
        $printType = "checkout";
        $lang = $request->get('lang');
        $roomHistory = Activity::findOrFail($id);
        $currentRoomHistory = json_decode($roomHistory->properties);
        $currentCheckedInRoomStayHistory = $this->buildCurrentCheckedInRoomStayHistory($currentRoomHistory);
        $view = $this->getPrintViewPage($printType);
        if (BranchHelper::getCurrentBranchId() == Config::get('constants.branches.hotel')) {
            $totalPayTax = $this->roomCalculationService->calculateTaxHotel($currentCheckedInRoomStayHistory);
            $result = $this->roomCalculationService->calculatePrintCheckoutForHotel($currentCheckedInRoomStayHistory, $currentCheckedInRoomStayHistory->id);
        } else {
            $totalPayTax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistory, $this->settingService);
            $result = $this->roomCalculationService->calculatePrintCheckout($currentCheckedInRoomStayHistory, $currentCheckedInRoomStayHistory->id);
        }

        return view($view, [
            'lang' => $lang != null ? $lang : 'th',
            'printAction' => Config::get('constants.print_checkout'),
            'totalPayTax' => $totalPayTax,
            'sumTotal' => $result['calculatedTotal'],
            'settingService' => $this->settingService,
            'currentCheckedInRoomStayHistroy' => $currentCheckedInRoomStayHistory
        ]);
    }

    private function getRoomUtilities()
    {
        return (object)[
            'electricUnit' => $this->settingService->get('electrict_price_per_unit', '1'),
            'vat' => $this->settingService->get('vat', '1'),
            'vat_special' => $this->settingService->get('vat_special', '1'),
            'vat_special_property' => $this->settingService->get('vat_special_property', '1'),
            'roomDaily' => $this->settingService->get('room_rate_daily', '1'),
            'roomMonthlyAir' => $this->settingService->get('room_rate_monthly_air', '1'),
            'roomMonthlyAir2' => $this->settingService->get('room_rate_monthly_air_2', '1'),
            'roomMonthlyFan' => $this->settingService->get('room_rate_monthly_fan', '1'),
            'roomMonthlyFan2' => $this->settingService->get('room_rate_monthly_fan_2', '1'),
            'tv' => $this->settingService->get('tv', '1'),
            'waterUnit' => $this->settingService->get('water_price_per_unit', '1'),
            'extra' => $this->settingService->getAllExtra()
        ];
    }

    private function getPrintViewPage($printType)
    {
        $printViewPage = "";
        switch (BranchHelper::getCurrentBranchId()) {
            case Config::get('constants.branches.resident') :
                if ($printType == "checkin") {
                    $printViewPage = "print.resident.print_checkin";
                } else if ($printType == "checkout") {
                    $printViewPage = "print.resident.print_checkout";
                } else {
                    $printViewPage = "print.resident.print";
                }
                break;
            case Config::get('constants.branches.boutique_jugkum') :
                if ($printType == "checkin") {
                    $printViewPage = "print.jugkum.print_checkin";
                } else if ($printType == "checkout") {
                    $printViewPage = "print.jugkum.print_checkout";
                } else {
                    $printViewPage = "print.jugkum.print";
                }
                break;
            case Config::get('constants.branches.boutique_tonthong') :
                if ($printType == "checkin") {
                    $printViewPage = "print.tonthong.print_checkin";
                } else if ($printType == "checkout") {
                    $printViewPage = "print.tonthong.print_checkout";
                } else {
                    $printViewPage = "print.tonthong.print";
                }
                break;
            case Config::get('constants.branches.hotel') :
                if ($printType == "checkin") {
                    $printViewPage = "print.hotel.print_checkin";
                } else if ($printType == "checkout") {
                    $printViewPage = "print.hotel.print_checkout";
                } else {
                    $printViewPage = "print.hotel.print";
                }
                break;
        }
        return $printViewPage;
    }

    private function buildCurrentCheckedInRoomStayHistory($currentRoomHistory)
    {
        // currentCheckedInRoomStayHistroy
        $currentCheckedInRoomStayHistory = $currentRoomHistory->currentCheckedInRoomStayHistroy;
        // room
        $currentCheckedInRoomStayHistory->room = $currentRoomHistory->room;
        // roomUtilityHistory
        $transformRoomUtilityHistory=  array();
        if($currentCheckedInRoomStayHistory->room_utility_history && sizeof($currentCheckedInRoomStayHistory->room_utility_history) > 0){
            foreach ($currentCheckedInRoomStayHistory->room_utility_history  as $key => $roomUtilityHistory){
                reset($currentCheckedInRoomStayHistory->room_utility_history);
                // appSettingEloquentStorage
                if($roomUtilityHistory->app_setting_eloquent_storage ){
                    $roomUtilityHistory->appSettingEloquentStorage = $roomUtilityHistory->app_setting_eloquent_storage;
                }else{
                    $roomUtilityHistory->appSettingEloquentStorage = new AppSettingEloquentStorage();
                }

                // check last element of array
                end($currentCheckedInRoomStayHistory->room_utility_history);
                if ($key === key($currentCheckedInRoomStayHistory->room_utility_history)){
                    array_push($transformRoomUtilityHistory , $this->getRoomCleaningCost($roomUtilityHistory->id));
                }

                unset($roomUtilityHistory->app_setting_eloquent_storage);
                array_push($transformRoomUtilityHistory , $roomUtilityHistory);
            }
        }

        $currentCheckedInRoomStayHistory->roomUtilityHistory = $transformRoomUtilityHistory;
        // remove property which parse from JSON
        unset($currentCheckedInRoomStayHistory->room_utility_history);
        return $currentCheckedInRoomStayHistory;
    }

    private function getRoomCleaningCost($roomStayHistoryId){
        $setting = $this->settingService->getSettingByKey('extra_5');
        $roomUtilityHistory = new RoomUtilityHistory();
        $roomUtilityHistory->setting_id = $setting->id;;
        $roomUtilityHistory->is_use = true;
        $roomUtilityHistory->room_stay_history_id = $roomStayHistoryId;
        return $roomUtilityHistory;
    }
}
