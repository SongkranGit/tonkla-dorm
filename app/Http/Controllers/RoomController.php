<?php

namespace App\Http\Controllers;

use App\Helpers\BranchHelper;
use App\Helpers\RoomServiceHelper;
use App\Http\Requests\Room\MoveRoomUpdateRequest;
use App\Http\Requests\Room\PrintRoomUpdateRequest;
use App\Http\Requests\Room\RoomUpdateRequest;
use App\IServices\IApprovalPrivilegeService;
use App\IServices\IRoomCalculationService;
use App\IServices\IRoomService;
use App\IServices\ISettingService;
use App\Models\Room;
use App\Models\RoomStayHistory;
use App\Models\RoomUtilityHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Redirect;
use Response;


class RoomController extends Controller
{

    private $roomService;
    private $settingService;
    private $roomCalculationService;
    private $approvalPrivilegeService;

    public function __construct(
        IRoomService $roomService,
        ISettingService $settingService,
        IRoomCalculationService $roomCalculationService,
        IApprovalPrivilegeService $approvalPrivilegeService
    )
    {
        $this->middleware(['auth']);
        $this->roomService = $roomService;
        $this->settingService = $settingService;
        $this->roomCalculationService = $roomCalculationService;
        $this->approvalPrivilegeService = $approvalPrivilegeService;
    }

    public function index()
    {
        $branch_id = session('current_branch_id');
        $rooms = Room::where('branch_id', $branch_id)->orderBy('sort_order')->get();
        // use for room status label
        $roomRateDaily = $this->settingService->get('room_rate_daily', 0);
        $roomRateDaily2 = $this->settingService->get('room_rate_daily_2', 0);
        $roomRateDaily3 = $this->settingService->get('room_rate_daily_3', 0);
        return view('room.index', [
            'rooms' => $rooms,
            'roomRateDaily' => $roomRateDaily,
            'roomRateDaily2' => $roomRateDaily2,
            'roomRateDaily3' => $roomRateDaily3,
        ]);
    }

    public function edit($id)
    {
        $currentUser = Auth::user();
        $canApproveCheckInCheckout = $this->approvalPrivilegeService->checkCanApproveByUserId($currentUser->id);
        $room = Room::where('branch_id', BranchHelper::getCurrentBranchId())->findOrFail($id);
        $availableRoom = Room::where('status', '=', 'available')->where('branch_id', BranchHelper::getCurrentBranchId())->get();
        $currentCheckedInRoomStayHistroy = $room->getCurrentCheckedInRoomStayHistroy();
        if ($currentCheckedInRoomStayHistroy == null) {
            $currentCheckedInRoomStayHistroy = new RoomStayHistory();
        }
        if ($currentCheckedInRoomStayHistroy->roomUtilityHistory == null) {
            $currentCheckedInRoomStayHistroy->roomUtilityHistory = new RoomUtilityHistory();
        }

        $roomRateDaily = $this->settingService->get('room_rate_daily', 0);
        $roomRateDaily2 = $this->settingService->get('room_rate_daily_2', 0);
        $roomRateDaily3 = $this->settingService->get('room_rate_daily_3', 0);
        $furniture = $this->settingService->get('extra_2', 0);


        return view('room.edit', [
            'room' => $room,
            'availableRoom' => $availableRoom,
            'isMovingRoom' => false,
            'utilities' => $this->getRoomUtilities(),
            'currentCheckedInRoomStayHistroy' => $currentCheckedInRoomStayHistroy,
            'canApproveCheckInCheckout' => $canApproveCheckInCheckout,
            'roomRateDaily' => $roomRateDaily,
            'roomRateDaily2' => $roomRateDaily2,
            'roomRateDaily3' => $roomRateDaily3,
            'furniture'=> $furniture
        ]);
    }

    public function update(RoomUpdateRequest $request, $id)
    {
        if ($request->isMovingRoom == true || $request->isMovingRoom == 'true') {
            $checkOutCurrentRoomRequest = new Request();
            $checkOutCurrentRoomRequest->offsetSet('status', 'available');
            $checkOutCurrentRoomRequest->offsetSet('isMovingRoom', true);
            $checkOutCurrentRoomResult = $this->roomService->updateRoom($checkOutCurrentRoomRequest, $request->currentRoomId);
            if ($checkOutCurrentRoomResult) {
                $this->roomService->updateRoom($request, $id);
            }
        } else {
            $this->roomService->updateRoom($request, $id);
        }
        return Redirect('room/' . $id . '/edit');
    }

    public function move(MoveRoomUpdateRequest $request)
    {
        $room = Room::findOrFail($request->selectedRoomId);

        $currentRoom = Room::findOrFail($request->currentRoomId);
        $room->status = $currentRoom->status;
        $room->keycards = $currentRoom->keycards;
        $currentCheckedInRoomStayHistroy = $currentRoom->getCurrentCheckedInRoomStayHistroy();
        if ($currentCheckedInRoomStayHistroy == null) {
            $currentCheckedInRoomStayHistroy = new RoomStayHistory();
        }
        if ($currentCheckedInRoomStayHistroy->roomUtilityHistory == null) {
            $currentCheckedInRoomStayHistroy->roomUtilityHistory = new RoomUtilityHistory();
        }

        return view('room.edit', [
            'room' => $room,
            'isMovingRoom' => true,
            'utilities' => $this->getRoomUtilities(),
            'currentCheckedInRoomStayHistroy' => $currentCheckedInRoomStayHistroy,
            'currentRoomId' => $currentRoom->id
        ]);
    }

    public function showPrintCheckin(Request $request, $id)
    {
        $currentCheckedInRoomStayHistroy = RoomStayHistory::findOrFail($id);
        $lang = $request->get('lang');
        $result = $this->roomCalculationService->calculatePrintCheckin($currentCheckedInRoomStayHistroy);
        $currentCheckedInRoomStayHistroy->offsetSet('calculated_total', $result['calculatedTotal']);
        $totalPayTax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistroy , $this->settingService);
        switch (BranchHelper::getCurrentBranchId()){
            case Config::get('constants.branches.resident') :
                $view = "print.resident.print_checkin";
                $totalPayTax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistroy , $this->settingService);
                break;
            case Config::get('constants.branches.boutique_jugkum') :
                $view = "print.jugkum.print_checkin";
                $totalPayTax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistroy , $this->settingService);
                break;
            case Config::get('constants.branches.boutique_tonthong') :
                $view = "print.tonthong.print_checkin";
                $totalPayTax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistroy , $this->settingService);
                break;
        }

        return view($view, [
            'lang' => $lang != null ? $lang : 'th',
            'printAction' => Config::get('constants.print_checkin') ,
            'totalPayTax' => $totalPayTax,
            'sumTotal' => $result['calculatedTotal'],
            'settingService'=> $this->settingService,
            'currentCheckedInRoomStayHistroy' => $result['currentCheckedInRoomStayHistroy']
        ]);
    }

    public function showPrint(Request $request, $id )
    {
        $currentCheckedInRoomStayHistory = RoomStayHistory::findOrFail($id);
        $lang = $request->get('lang');
        switch (BranchHelper::getCurrentBranchId()){
            case Config::get('constants.branches.resident') :
                $view = "print.resident.print";
                $result = $this->roomCalculationService->calculateTotal($currentCheckedInRoomStayHistory);
                $currentCheckedInRoomStayHistory->offsetSet('calculated_total', $result['calculatedTotal']);
                $totalPayTax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistory , $this->settingService);
                break;
            case Config::get('constants.branches.boutique_jugkum') :
                $view = "print.jugkum.print";
                $result = $this->roomCalculationService->calculateTotal($currentCheckedInRoomStayHistory);
                $currentCheckedInRoomStayHistory->offsetSet('calculated_total', $result['calculatedTotal']);
                $totalPayTax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistory , $this->settingService);
                break;
            case Config::get('constants.branches.boutique_tonthong') :
                $view = "print.tonthong.print";
                $result = $this->roomCalculationService->calculateTotal($currentCheckedInRoomStayHistory);
                $currentCheckedInRoomStayHistory->offsetSet('calculated_total', $result['calculatedTotal']);
                $totalPayTax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistory , $this->settingService);
                break;
            case Config::get('constants.branches.hotel') :
                $view = "print.hotel.print";
                $result = $this->roomCalculationService->calculateTotalForHotelBranch($currentCheckedInRoomStayHistory);
                $currentCheckedInRoomStayHistory->offsetSet('calculated_total', $result['calculatedTotal']);
                $totalPayTax = $this->roomCalculationService->calculateTaxHotel($currentCheckedInRoomStayHistory);
                break;
        }

        $sumTotal = $result['calculatedTotal'];
        return view($view, [
            'lang' => $lang != null ? $lang : 'th',
            'printAction' => Config::get('constants.print') ,
            'totalPayTax' => $totalPayTax,
            'sumTotal' => $sumTotal,
            'settingService'=> $this->settingService,
            'currentCheckedInRoomStayHistroy' => $result['currentCheckedInRoomStayHistroy']
        ]);
    }

    public function showCheckoutPrint(Request $request, $id)
    {
        $currentCheckedInRoomStayHistory = RoomStayHistory::findOrFail($id);
        $lang = $request->get('lang');
        switch (BranchHelper::getCurrentBranchId()){
            case Config::get('constants.branches.resident') :
                $view = "print.resident.print_checkout";
                $result = $this->roomCalculationService->calculatePrintCheckout($currentCheckedInRoomStayHistory, $id);
                $totalPayTax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistory , $this->settingService);
                break;
            case Config::get('constants.branches.boutique_jugkum') :
                $view = "print.jugkum.print_checkout";
                $result = $this->roomCalculationService->calculatePrintCheckout($currentCheckedInRoomStayHistory, $id);
                $totalPayTax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistory , $this->settingService);
                break;
            case Config::get('constants.branches.boutique_tonthong') :
                $view = "print.tonthong.print_checkout";
                $result = $this->roomCalculationService->calculatePrintCheckout($currentCheckedInRoomStayHistory, $id);
                $totalPayTax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistory , $this->settingService);
                break;
            case Config::get('constants.branches.hotel') :
                $view = "print.hotel.print_checkout";
                $result = $this->roomCalculationService->calculatePrintCheckoutForHotel($currentCheckedInRoomStayHistory, $id);
                $totalPayTax = $this->roomCalculationService->calculateTaxHotel($currentCheckedInRoomStayHistory);
                break;
        }
        $currentCheckedInRoomStayHistory->offsetSet('calculated_total', $result['calculatedTotal']);
        $sumTotal = $result['calculatedTotal'];
        return view($view, [
            'lang' => $lang != null ? $lang : 'th',
            'printAction' => Config::get('constants.print_checkout') ,
            'totalPayTax' => $totalPayTax,
            'sumTotal' => $sumTotal,
            'settingService'=> $this->settingService,
            'currentCheckedInRoomStayHistroy' => $result['currentCheckedInRoomStayHistroy']
        ]);
    }

    public function updatePrint(PrintRoomUpdateRequest $request)
    {
        $room = Room::findOrFail($request->room_id);
        $roomStatus = 'room_printed';
        if ($request->status == 'print_checkout') {
            // $roomStatus = 'room_printed_checkout';
        } else if ($request->status == 'print_checkin') {
            $roomStatus = 'room_printed_checkin';
        }
        $this->roomService->setActivityLog($roomStatus, $room);

        return Response::json();
    }

    public function generateReceiptNumber(){
        $receiptRunningNumber = $this->roomService->generateReceiptRunningNumber();
        echo $receiptRunningNumber;
    }

    private function getRoomUtilities()
    {
        return (object)[
            'electricUnit' => $this->settingService->get('electrict_price_per_unit', '1'),
            'vat' => $this->settingService->get('vat', '1'),
            'vat_special' => $this->settingService->get('vat_special', '1'),
            'vat_special_property' => $this->settingService->get('vat_special_property', '1'),
            'roomDaily' => $this->settingService->get('room_rate_daily', '1'),
            'roomDaily2' => $this->settingService->get('room_rate_daily_2', '1'),
            'roomDaily3' => $this->settingService->get('room_rate_daily_3', '1'),
            'roomMonthlyAir' => $this->settingService->get('room_rate_monthly_air', '1'),
            'roomMonthlyAir2' => $this->settingService->get('room_rate_monthly_air_2', '1'),
            'roomMonthlyFan' => $this->settingService->get('room_rate_monthly_fan', '1'),
            'roomMonthlyFan2' => $this->settingService->get('room_rate_monthly_fan_2', '1'),
            'roomMonthlyHouse' => $this->settingService->get('room_rate_monthly_house', '1'),
            'tv' => $this->settingService->get('tv', '1'),
            'waterUnit' => $this->settingService->get('water_price_per_unit', '1'),
            'extra' => $this->settingService->getAllExtra()
        ];
    }


}
