<?php

namespace App\Services;

use App\Helpers\BranchHelper;
use App\Helpers\RoomServiceHelper;
use App\Models\ReceiptNumberGenerator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\IServices\IRoomService;
use App\IServices\ISettingService;
use App\IServices\IKeycardService;
use App\IServices\IRoomUtilityHistoryService;

use App\Models\Room;
use App\Models\RoomStayHistory;
use App\Models\RoomUtilityHistory;
use App\Models\Keycard;
use PHPUnit\Framework\MockObject\Builder\Match;
use Spatie\Activitylog\Models\Activity;

class RoomService implements IRoomService
{
    private $settingService;
    private $keycardService;
    private $roomUtilityHistoryService;

    public function __construct(
        ISettingService            $settingService,
        IRoomUtilityHistoryService $roomUtilityHistoryService,
        IKeycardService            $keycardService)
    {
        $this->settingService = $settingService;
        $this->keycardService = $keycardService;
        $this->roomUtilityHistoryService = $roomUtilityHistoryService;
    }

    public function updateRoom(Request $request, $id)
    {
        $room = Room::where('branch_id', BranchHelper::getCurrentBranchId())->findOrFail($id);
        if ($request->status == 'available' && $room->status == 'available') {
            return true;
        }

        $currentCheckedInRoomStayHistroy = $room->getCurrentCheckedInRoomStayHistroy();
        // Move room
        if ($request->currentRoomId != null && ($request->isMovingRoom == true || $request->isMovingRoom == 'true')) {
            $currentRoom = Room::where('branch_id', BranchHelper::getCurrentBranchId())->findOrFail($request->currentRoomId);
            $this->setActivityLog('room_moved_to', $currentRoom, ' : ' . $room->room_no);
            if ($request->status != 'available') {
                $this->setActivityLog('room_moved_from', $room, ' : ' . $currentRoom->room_no);
            }
        } else {
            //Checkout set ค่าทำความสะอาด and then updated the log
            $setting = $this->settingService->getSettingByKey('extra_5');
            $room_clean_setting_id = $setting->id;
            if ($request->status == 'available'
                && $room->status != 'available'
                && $currentCheckedInRoomStayHistroy->roomUtilityHistory->where('setting_id', $room_clean_setting_id)->first() == null) {
                $roomUtilityHistory = new RoomUtilityHistory();
                $roomUtilityHistory->setting_id = $room_clean_setting_id;
                $roomUtilityHistory->is_use = true;
                $roomUtilityHistory->room_stay_history_id = $currentCheckedInRoomStayHistroy->id;
                $roomUtilityHistory->save();
            }
        }

        //Checkout
        if ($request->status == 'available' && $room->status != 'available') {
            $this->setActivityLog('room_printed_checkout', $room);
            $room->status = 'available';
            if ($currentCheckedInRoomStayHistroy != null) {
                $currentCheckedInRoomStayHistroy->status = 'checked_out';
                if ($request->currentRoomId == null && ($request->isMovingRoom == true || $request->isMovingRoom == 'true')) {
                    $currentCheckedInRoomStayHistroy->is_moved = true;
                }
                $currentCheckedInRoomStayHistroy->save();
            }
            $room->keycards()->delete();
        } else {
            // create / update room (Checked In)
            if ($currentCheckedInRoomStayHistroy == null) {
                $currentCheckedInRoomStayHistroy = new RoomStayHistory();
                $currentCheckedInRoomStayHistroy->status = 'checked_in';
                $currentCheckedInRoomStayHistroy->room_id = $room->id;
            }

            $currentCheckedInRoomStayHistroy = $this->getAttributeMapper($currentCheckedInRoomStayHistroy, $request);

            if (RoomServiceHelper::checkRoomTypeDaily($request->status)) {
                $room->status = $request->status;

                if (BranchHelper::isBranchHotel()) {
                    $currentCheckedInRoomStayHistroy = $this->calculateRoomDailyFeeForBranchHotel($currentCheckedInRoomStayHistroy, $request->extras);
                    $currentCheckedInRoomStayHistroy->save();
                    //Extras
                    $this->roomUtilityHistoryService->deleteRoomUtilityHistories($request->delete_extras);
                    $this->roomUtilityHistoryService->upsertRoomUtilityHistories($request->extras, $currentCheckedInRoomStayHistroy->id);
                } else {
                    $currentCheckedInRoomStayHistroy = $this->calculateRoomDailyFee($currentCheckedInRoomStayHistroy, $request->extras);
                    $currentCheckedInRoomStayHistroy->roomUtilityHistory()->delete();
                    $currentCheckedInRoomStayHistroy->save();
                }
            } elseif ($request->status == 'monthly_air' || $request->status == 'monthly_fan' || $request->status == 'monthly_air_2' || $request->status == 'monthly_fan_2' || $request->status == 'monthly_house') {
                $room->status = $request->status;
                $currentCheckedInRoomStayHistroy = $this->calculateRoomMonthlyFee($currentCheckedInRoomStayHistroy, $request->extras);
                $currentCheckedInRoomStayHistroy->save();
                //Extras
                $this->roomUtilityHistoryService->deleteRoomUtilityHistories($request->delete_extras);
                $this->roomUtilityHistoryService->upsertRoomUtilityHistories($request->extras, $currentCheckedInRoomStayHistroy->id);
            }

            //Keycards
            $this->keycardService->deleteKeycards($request->delete_keycards);
            $this->keycardService->upsertKeycards($request->keycards, $room->id);

            $this->setActivityLog('room_updated', $room);
        }

        $room->save();

        return true;
    }

    public function calculateRoomDailyFee($currentCheckedInRoomStayHistroy, $extras)
    {
        $rental_fee = $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ? $currentCheckedInRoomStayHistroy->custom_rental_fee : $currentCheckedInRoomStayHistroy->rental_fee;
        $discount = $currentCheckedInRoomStayHistroy->discount != null ? $currentCheckedInRoomStayHistroy->discount : 0;
        if ($rental_fee >= $discount) {
            $totalDate = 1;
            if ($currentCheckedInRoomStayHistroy->check_in != null && $currentCheckedInRoomStayHistroy->check_out != null) {
                $dateDiff = Carbon::createFromFormat('d/m/Y H:i:s', $currentCheckedInRoomStayHistroy->check_out)->startOfDay()->diffInDays(Carbon::createFromFormat('d/m/Y H:i:s', $currentCheckedInRoomStayHistroy->check_in)->startOfDay());
                if ($dateDiff > 1) {
                    $totalDate = $dateDiff;
                }
            }
            // Calculate rental with dates
            $rental_fee = $rental_fee * $totalDate;

            // Tax
            if ($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee) {
                $vat = $this->settingService->get('vat', 1);
                $tax = 0;
                $customRentalFree = intval($currentCheckedInRoomStayHistroy->custom_rental_fee);
                $rentalFree = intval($currentCheckedInRoomStayHistroy->rental_fee);
                $diffPay = $customRentalFree - $rentalFree;
                if ($diffPay > 0) {
                    $tax = $diffPay * ($vat / 100);
                }
                $currentCheckedInRoomStayHistroy->total_price = $customRentalFree + (int)$tax;
            } else {
                $currentCheckedInRoomStayHistroy->total_price = $rental_fee;
            }

            // Discount
            $currentCheckedInRoomStayHistroy->total_price -= $currentCheckedInRoomStayHistroy->discount; // minus discount before calculate vat

            // Extra
            if (BranchHelper::isBranchHotel()) {
                $extraVal = $this->calculateExtra($currentCheckedInRoomStayHistroy, $extras);
                if ($extraVal != 0) {
                    $currentCheckedInRoomStayHistroy->total_price += $extraVal;
                }
            }
        } else {
            $currentCheckedInRoomStayHistroy->total_price = 0;
        }

        return $currentCheckedInRoomStayHistroy;
    }

    public function calculateRoomDailyFeeForBranchHotel($currentCheckedInRoomStayHistroy, $extras)
    {
        $totalDate = 1;
        $rental_fee = $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ? $currentCheckedInRoomStayHistroy->custom_rental_fee : $currentCheckedInRoomStayHistroy->rental_fee;

        if ($currentCheckedInRoomStayHistroy->check_in != null && $currentCheckedInRoomStayHistroy->check_out != null) {
            $totalDate = Carbon::createFromFormat('d/m/Y H:i:s', $currentCheckedInRoomStayHistroy->check_out)->startOfDay()->diffInDays(Carbon::createFromFormat('d/m/Y H:i:s', $currentCheckedInRoomStayHistroy->check_in)->startOfDay());
            if ($totalDate == 0) {
                $totalDate = 1;
            }
        }

        $totalPrice = $rental_fee * $totalDate;

        // Tax
        if ($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee) {
            $vat = $this->settingService->get('vat', 1);
            $vat = $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ? $vat : 0;
            if ($vat > 0) {
                $tax = ($vat / 100) * $totalPrice;
                $totalPrice += $tax;
            }
        }

        // Discount
        $discount = $currentCheckedInRoomStayHistroy->discount != null ? $currentCheckedInRoomStayHistroy->discount : 0;
        if ($discount > 0) {
            $totalPrice -= $discount;
        }

        // Extra
        $extraVal = $this->calculateExtra($currentCheckedInRoomStayHistroy, $extras);
        if ($extraVal && $extraVal > 0) {
            $totalPrice += $extraVal;
        }

        // Deposit (ค่ามัดจำล่วงหน้า)
        if ($currentCheckedInRoomStayHistroy->deposit > 0) {
            $totalPrice += $currentCheckedInRoomStayHistroy->deposit;
        }

        // Deposit_other (ค่ามัดจำอื่นๆ)
        if ($currentCheckedInRoomStayHistroy->deposit_other > 0) {
            $totalPrice += $currentCheckedInRoomStayHistroy->deposit_other;
        }

        // Deposit_keycard (ค่ามัดจำคีย์การ์ด)
        if ($currentCheckedInRoomStayHistroy->deposit_keycard > 0) {
            $totalPrice += $currentCheckedInRoomStayHistroy->deposit_keycard;
        }

        // Maintenance
        if ($currentCheckedInRoomStayHistroy->room_maintenance_cost > 0) {
            $totalPrice += $currentCheckedInRoomStayHistroy->room_maintenance_cost;
        }

        $currentCheckedInRoomStayHistroy->total_price = $totalPrice;

        return $currentCheckedInRoomStayHistroy;
    }

    public function calculateRoomMonthlyFee($currentCheckedInRoomStayHistroy, $extras)
    {
        $vat_special = $this->settingService->get('vat_special', 1);
        $vat_special_property = $this->settingService->get('vat_special_property', 1);
        $electrictPricePerUnit = $this->settingService->get('electrict_price_per_unit', 1);
        $waterPricePerUnit = $this->settingService->get('water_price_per_unit', 1);
        $currentCheckedInRoomStayHistroy->electric_meter_fee = 0;
        $currentCheckedInRoomStayHistroy->water_meter_fee = 0;

        $currentCheckedInRoomStayHistroy->total_price = $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ? $currentCheckedInRoomStayHistroy->custom_rental_fee : $currentCheckedInRoomStayHistroy->rental_fee;

        if ($currentCheckedInRoomStayHistroy->electric_meter_end >= $currentCheckedInRoomStayHistroy->electric_meter_start) {
            $currentCheckedInRoomStayHistroy->electric_meter_unit = $currentCheckedInRoomStayHistroy->electric_meter_end - $currentCheckedInRoomStayHistroy->electric_meter_start;
            $currentCheckedInRoomStayHistroy->electric_meter_fee = $currentCheckedInRoomStayHistroy->electric_meter_unit * $electrictPricePerUnit;
        }
        if ($currentCheckedInRoomStayHistroy->water_meter_end >= $currentCheckedInRoomStayHistroy->water_meter_start) {
            $currentCheckedInRoomStayHistroy->water_meter_unit = $currentCheckedInRoomStayHistroy->water_meter_end - $currentCheckedInRoomStayHistroy->water_meter_start;
            $currentCheckedInRoomStayHistroy->water_meter_fee = $currentCheckedInRoomStayHistroy->water_meter_unit * $waterPricePerUnit;
        }

        $extraVal = 0;
        if ($extras != null && sizeof($extras) > 0) {
            foreach ($extras as $item) {
                $setting = $this->settingService->getByStorageId($item['setting_id']);
                $itemVal = $setting->value;
                if ($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee && $setting->is_use_calculate_vat) {
                    $currentCheckedInRoomStayHistroy->total_price += $itemVal;
                } else {
                    $extraVal += $itemVal;
                }
            }
        }

        // Calculate Tax
        if ($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee) {
            $tax = 0;
            $furnitureCost = $this->settingService->get('extra_2', 1);
            $customRentalFree = intval($currentCheckedInRoomStayHistroy->custom_rental_fee);
            $rentalFree = intval($currentCheckedInRoomStayHistroy->rental_fee);
            $diffPay = $customRentalFree - $rentalFree;
            if ($diffPay > 0) {
                $tax = $diffPay * ($vat_special_property / 100);
            }
            $currentCheckedInRoomStayHistroy->total_price += $tax;
            // $currentCheckedInRoomStayHistroy->total_price -= $furnitureCost ; // delete furniture cost
        }

        // Electric meter / Water meter / room maintenance cost
        $currentCheckedInRoomStayHistroy->total_price += $currentCheckedInRoomStayHistroy->electric_meter_fee
            + $currentCheckedInRoomStayHistroy->water_meter_fee
            + $extraVal;

        // dd($currentCheckedInRoomStayHistroy->total_price);

        return $currentCheckedInRoomStayHistroy;
    }

    public function getAttributeMapper($currentCheckedInRoomStayHistroy, Request $request)
    {
        $currentCheckedInRoomStayHistroy->renter_firstname = $request->renter_firstname;
        $currentCheckedInRoomStayHistroy->renter_lastname = $request->renter_lastname;
        $currentCheckedInRoomStayHistroy->renter_telephone = $request->renter_telephone;

        $currentCheckedInRoomStayHistroy->renter_id_card_no = $request->renter_id_card_no;
        $currentCheckedInRoomStayHistroy->renter_tax_payer_no = $request->renter_tax_payer_no;
        $currentCheckedInRoomStayHistroy->renter_current_address = $request->renter_current_address;

        $currentCheckedInRoomStayHistroy->check_in = $request->check_in != null ? Carbon::createFromFormat('d/m/Y H:i:s', $request->check_in)->format('Y-m-d H:i:s') : null;
        $currentCheckedInRoomStayHistroy->check_out = $request->check_out != null ? Carbon::createFromFormat('d/m/Y H:i:s', $request->check_out)->format('Y-m-d H:i:s') : null;

        $currentCheckedInRoomStayHistroy->deposit = $request->deposit;
        $currentCheckedInRoomStayHistroy->deposit_keycard = $request->deposit_keycard;
        $currentCheckedInRoomStayHistroy->deposit_other = $request->deposit_other;

        $currentCheckedInRoomStayHistroy->rental_fee = $request->rental_fee;
        $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee = $request->is_use_custom_rental_fee == 'true' ? true : false;
        $currentCheckedInRoomStayHistroy->custom_rental_fee = $request->custom_rental_fee;
        $currentCheckedInRoomStayHistroy->discount = $request->discount;

        $currentCheckedInRoomStayHistroy->discount_remark = $request->discount_remark;

        if (RoomServiceHelper::checkRoomTypeDaily($request->status)) {
            $currentCheckedInRoomStayHistroy->electric_meter_start = null;
            $currentCheckedInRoomStayHistroy->electric_meter_end = null;
            $currentCheckedInRoomStayHistroy->water_meter_start = null;
            $currentCheckedInRoomStayHistroy->water_meter_end = null;

            $currentCheckedInRoomStayHistroy->renter_school = null;
            $currentCheckedInRoomStayHistroy->renter_school_class = null;
            $currentCheckedInRoomStayHistroy->renter_parent_firstname = null;
            $currentCheckedInRoomStayHistroy->renter_parent_lastname = null;
            $currentCheckedInRoomStayHistroy->renter_parent_id_card_no = null;
            $currentCheckedInRoomStayHistroy->renter_parent_telephone = null;
            $currentCheckedInRoomStayHistroy->room_maintenance_cost = $request->room_maintenance_cost;
        } else if ($request->status == 'monthly_air' || $request->status == 'monthly_fan' || $request->status == 'monthly_air_2' || $request->status == 'monthly_fan_2' || $request->status == 'monthly_house') {
            $currentCheckedInRoomStayHistroy->electric_meter_start = $request->electric_meter_start;
            $currentCheckedInRoomStayHistroy->electric_meter_end = $request->electric_meter_end;
            $currentCheckedInRoomStayHistroy->water_meter_start = $request->water_meter_start;
            $currentCheckedInRoomStayHistroy->water_meter_end = $request->water_meter_end;

            $currentCheckedInRoomStayHistroy->renter_school = $request->renter_school;
            $currentCheckedInRoomStayHistroy->renter_school_class = $request->renter_school_class;
            $currentCheckedInRoomStayHistroy->renter_parent_firstname = $request->renter_parent_firstname;
            $currentCheckedInRoomStayHistroy->renter_parent_lastname = $request->renter_parent_lastname;
            $currentCheckedInRoomStayHistroy->renter_parent_id_card_no = $request->renter_parent_id_card_no;
            $currentCheckedInRoomStayHistroy->renter_parent_telephone = $request->renter_parent_telephone;
            $currentCheckedInRoomStayHistroy->room_maintenance_cost = $request->room_maintenance_cost;
        }

        return $currentCheckedInRoomStayHistroy;
    }

    public function setActivityLog($actionType, $room, $description = "")
    {
        $room->keycards;
        $room->equipments;
        $currentCheckedInRoomStayHistroy = $room->getCurrentCheckedInRoomStayHistroy();
        if ($currentCheckedInRoomStayHistroy != null) {
            $roomUtilities = $currentCheckedInRoomStayHistroy->roomUtilityHistory;
            if ($roomUtilities != null && sizeof($roomUtilities) > 0) {
                foreach ($roomUtilities as $roomUtility) {
                    $roomUtility->appSettingEloquentStorage;
                }
            }
        }

        $properties = [
            'room' => $room,
            'currentCheckedInRoomStayHistroy' => $currentCheckedInRoomStayHistroy
        ];
        activity($actionType)
            ->causedBy(\Auth::user())
            ->performedOn($room)
            ->withProperties($properties)
            ->tap(function (Activity $activity) {
                $activity->branch_id = session('current_branch_id');
            })
            ->log($description);
    }

    public function generateReceiptRunningNumber()
    {
        $running = 1;
        $current_branch_id = BranchHelper::getCurrentBranchId();
        $receiptNumberGenerator = ReceiptNumberGenerator::where('branch_id', $current_branch_id)->first();
        if($receiptNumberGenerator == null) {
            // insert new running number
            $receiptNumberGenerator = new ReceiptNumberGenerator;
            $receiptNumberGenerator->branch_id = $current_branch_id;
            $receiptNumberGenerator->running_number = 1;
            $receiptNumberGenerator->save();
        } else {
            // update running number
            $running = $receiptNumberGenerator->running_number + 1;
            if($running == 10001){
                  $running = 1;
            }
            $receiptNumberGenerator->running_number = $running;
            $receiptNumberGenerator->save();
        }
        $number = sprintf('%04d',$running);
        return $current_branch_id ."-". $number;
    }

    private function calculateExtra($currentCheckedInRoomStayHistroy, $extras)
    {
        $extraVal = 0;
        if ($extras != null && sizeof($extras) > 0) {
            foreach ($extras as $item) {
                $setting = $this->settingService->getByStorageId($item['setting_id']);
                $itemVal = $setting->value;
                if ($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee && $setting->is_use_calculate_vat) {
                    //$currentCheckedInRoomStayHistroy->total_price  += ($itemVal + ($itemVal * ($vat_special/100)));
                    $currentCheckedInRoomStayHistroy->total_price += $itemVal;
                } else {
                    $extraVal += $itemVal;
                }

            }
        }
        return $extraVal;
    }

}
