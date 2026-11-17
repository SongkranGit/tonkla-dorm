<?php

namespace App\Services;

use App\Helpers\BranchHelper;
use App\Helpers\RoomServiceHelper;
use Illuminate\Http\Request;
use App\IServices\ISettingService;
use App\IServices\IRoomCalculationService;
use App\Models\RoomUtilityHistory;
use Illuminate\Support\Facades\Config;
use function Psy\debug;

class RoomCalculationService implements IRoomCalculationService
{

    private $settingService;

    public function __construct(
        ISettingService $settingService
    )
    {
        $this->settingService = $settingService;
    }

    public function calculatePrintCheckin($currentCheckedInRoomStayHistroy)
    {
        // Daily
        if (RoomServiceHelper::isRoomTypeDaily($currentCheckedInRoomStayHistroy)) {
            if ($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee) {
                $calculatedTotal = $currentCheckedInRoomStayHistroy->custom_rental_fee;
                $totalDate = RoomServiceHelper::calculateCheckinDates($currentCheckedInRoomStayHistroy);
                $calculatedTotal = $calculatedTotal * $totalDate;
            } else {
                $calculatedTotal = $currentCheckedInRoomStayHistroy->rental_fee;
                $totalDate = RoomServiceHelper::calculateCheckinDates($currentCheckedInRoomStayHistroy);
                $calculatedTotal = $calculatedTotal * $totalDate;
            }
        } else {
            // Monthly
            if ($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee) {
                $calculatedTotal = $currentCheckedInRoomStayHistroy->custom_rental_fee;
            } else {
                $calculatedTotal = $currentCheckedInRoomStayHistroy->rental_fee;
                $furnitureCost = RoomServiceHelper::getFurnitureCost($currentCheckedInRoomStayHistroy->roomUtilityHistory, $this->settingService);
                $calculatedTotal += $furnitureCost;
            }
        }

        if ($currentCheckedInRoomStayHistroy->deposit > 0) {
            $calculatedTotal += $currentCheckedInRoomStayHistroy->deposit;
        }
        if ($currentCheckedInRoomStayHistroy->deposit_keycard > 0) {
            $calculatedTotal += $currentCheckedInRoomStayHistroy->deposit_keycard;
        }
        if ($currentCheckedInRoomStayHistroy->deposit_other > 0) {
            $calculatedTotal += $currentCheckedInRoomStayHistroy->deposit_other;
        }
        if ($currentCheckedInRoomStayHistroy->discount > 0) {
            $calculatedTotal -= $currentCheckedInRoomStayHistroy->discount;
        }

        $extraCost = $this->calculateExtra($currentCheckedInRoomStayHistroy->roomUtilityHistory); // ค่าพิเศษ
        if($extraCost > 0){
            $calculatedTotal += $extraCost;
        }

        return [
            'calculatedTotal' => $calculatedTotal,
            'currentCheckedInRoomStayHistroy' => $currentCheckedInRoomStayHistroy
        ];
    }

    public function calculatePrintCheckout($currentCheckedInRoomStayHistroy, $id)
    {
        $isIncludedCheckOut = false;
        $calculatedTotal = 0;
        $roomRate = 0;

        if ($currentCheckedInRoomStayHistroy->room->status != 'available') {
            $isIncludedCheckOut = true;
            // Daily
            if (RoomServiceHelper::isRoomTypeDaily($currentCheckedInRoomStayHistroy)) {
                $roomRate = $currentCheckedInRoomStayHistroy->custom_rental_fee;
                $totalDate = RoomServiceHelper::calculateCheckinDates($currentCheckedInRoomStayHistroy);
                $roomRate = $roomRate * $totalDate;
                $cleaningRoomCost = 0;
            } else {
                // (Monthly)
                $setting = $this->settingService->getSettingByKey('extra_5');
                $room_clean_setting_id = $setting->id;
                $cleaningRoomCost = $this->settingService->get('extra_5', '1'); // ค่าทำความสะอาด

                if(is_array($currentCheckedInRoomStayHistroy->roomUtilityHistory)){
                    $hasRoomCleanSetting = $currentCheckedInRoomStayHistroy->roomUtilityHistory && sizeof($currentCheckedInRoomStayHistroy->roomUtilityHistory) > 0;
                }else{
                    $hasRoomCleanSetting = $currentCheckedInRoomStayHistroy->roomUtilityHistory->where('setting_id', $room_clean_setting_id)->first() != null;
                }

                if (!isset($currentCheckedInRoomStayHistroy->roomUtilityHistory)
                    || $currentCheckedInRoomStayHistroy->roomUtilityHistory == null
                    || !$hasRoomCleanSetting
                ) {
                    $roomUtilityHistory = new RoomUtilityHistory();
                    $roomUtilityHistory->setting_id = $room_clean_setting_id;
                    $roomUtilityHistory->is_use = true;
                    $roomUtilityHistory->room_stay_history_id = $id;
                    if (!isset($currentCheckedInRoomStayHistroy->roomUtilityHistory)
                        || $currentCheckedInRoomStayHistroy->roomUtilityHistory == null) {
                        $currentCheckedInRoomStayHistroy->roomUtilityHistory = collect();
                    }
                    $currentCheckedInRoomStayHistroy->roomUtilityHistory->push($roomUtilityHistory);
                }
            }

            $extraCost = $this->calculateExtra($currentCheckedInRoomStayHistroy->roomUtilityHistory); // ค่าพิเศษ

            // Deposit cost
            $totalDeposit = $currentCheckedInRoomStayHistroy->deposit
                + $currentCheckedInRoomStayHistroy->discount
                + $currentCheckedInRoomStayHistroy->deposit_other;

            // Paid cost
            $totalPaid = $currentCheckedInRoomStayHistroy->electric_meter_fee
                + $currentCheckedInRoomStayHistroy->water_meter_fee
                + $currentCheckedInRoomStayHistroy->room_maintenance_cost
                + $cleaningRoomCost
                + $extraCost;

            // Case พิมพ์แรกเข้านี้จะไม่คำนวนภาษี ดั้งนั้นต้องเอา total_price - tax เพราะค่านี้ได้มีการจัดเก็บตั้งแต่หน้าจอแล้ว
            if ($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee) {
                $tax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistroy, $this->settingService);
                if ($tax > 0) {
                    $totalPaid += $tax;
                }
            }

            $calculatedTotal = $roomRate - ($totalDeposit - $totalPaid);
        }

        return [
            'calculatedTotal' => $calculatedTotal,
            'isIncludedCheckOut' => $isIncludedCheckOut,
            'currentCheckedInRoomStayHistroy' => $currentCheckedInRoomStayHistroy
        ];
    }


    public function calculatePrintCheckoutForHotel($currentCheckedInRoomStayHistroy, $id)
    {
        $totalDate = RoomServiceHelper::calculateCheckinDates($currentCheckedInRoomStayHistroy);
        // With Tax
        if ($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee) {
            $calculatedTotal = $currentCheckedInRoomStayHistroy->custom_rental_fee * $totalDate;
            // Tax
            $tax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistroy, $this->settingService);
            $calculatedTotal += $tax;
        }else{
            $calculatedTotal =  $currentCheckedInRoomStayHistroy->rental_fee * $totalDate;
        }

        // Extra
        $calculatedTotal += $this->calculateExtra($currentCheckedInRoomStayHistroy->roomUtilityHistory);
        // Maintenance cost
        $calculatedTotal += $currentCheckedInRoomStayHistroy->room_maintenance_cost;
        // Deposit Other
        $calculatedTotal -= $currentCheckedInRoomStayHistroy->deposit_other;
        // Deposit
        $calculatedTotal -= $currentCheckedInRoomStayHistroy->deposit;
        // Keycard
        $calculatedTotal -= $currentCheckedInRoomStayHistroy->deposit_keycard;
        // CASE : New logic (18/07/2020) , show discount each end of month
        $calculatedTotal -= $currentCheckedInRoomStayHistroy->discount;

        return [
            'calculatedTotal' => $calculatedTotal,
            'currentCheckedInRoomStayHistroy' => $currentCheckedInRoomStayHistroy
        ];
    }


    public function calculateTotal($currentCheckedInRoomStayHistroy)
    {
        $isIncludedCheckOut = false;
        // Daily
        if (RoomServiceHelper::isRoomTypeDaily($currentCheckedInRoomStayHistroy)) {
            if ($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee) {
                // Room rate total
                $calculatedTotal = $currentCheckedInRoomStayHistroy->custom_rental_fee; // ดึงมาจาก custom rental fee
                $totalDate = RoomServiceHelper::calculateCheckinDates($currentCheckedInRoomStayHistroy);
                $calculatedTotal = $calculatedTotal * $totalDate;
                // Tax
                $tax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistroy, $this->settingService);
                $calculatedTotal += $tax;
            } else {
                // Room rate total
                $calculatedTotal = $currentCheckedInRoomStayHistroy->rental_fee; // ดึงมาจาก custom rental fee
                $totalDate = RoomServiceHelper::calculateCheckinDates($currentCheckedInRoomStayHistroy);
                $calculatedTotal = $calculatedTotal * $totalDate;
            }
            // CASE : New logic (18/07/2020) , show discount each end of month
            $calculatedTotal -= $currentCheckedInRoomStayHistroy->discount;
        } else {
            // Monthly
            if ($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee) {
                $calculatedTotal = $currentCheckedInRoomStayHistroy->custom_rental_fee; // ดึงมาจาก custom rental fee
                // Case : เบิกภาษีแต่ไม่มีการเปลี่ยนแปลงค่าห้อง (input field)
                if ($currentCheckedInRoomStayHistroy->custom_rental_fee === $currentCheckedInRoomStayHistroy->rental_fee) {
                    $furnitureCost = RoomServiceHelper::getFurnitureCost($currentCheckedInRoomStayHistroy->roomUtilityHistory, $this->settingService);
                    $calculatedTotal = $currentCheckedInRoomStayHistroy->rental_fee + $furnitureCost;
                }
                // Tax
                $tax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistroy, $this->settingService);
                $calculatedTotal += $tax;
            } else {
                $calculatedTotal = $currentCheckedInRoomStayHistroy->rental_fee; // ดึงมาจาก custom rental fee
                // Furniture
                $furnitureCost = RoomServiceHelper::getFurnitureCost($currentCheckedInRoomStayHistroy->roomUtilityHistory, $this->settingService);
                $calculatedTotal += $furnitureCost;
            }

            // Extra
            $extraCost = $this->calculateExtra($currentCheckedInRoomStayHistroy->roomUtilityHistory); // ค่าพิเศษ
            $calculatedTotal += $extraCost;
            // Electric
            $calculatedTotal += $currentCheckedInRoomStayHistroy->electric_meter_fee;
            // Water
            $calculatedTotal += $currentCheckedInRoomStayHistroy->water_meter_fee;
            // CASE : New logic (18/07/2020) , show discount each end of month
            $calculatedTotal -= $currentCheckedInRoomStayHistroy->discount;
        }

        return [
            'calculatedTotal' => $calculatedTotal,
            'isIncludedCheckOut' => $isIncludedCheckOut,
            'currentCheckedInRoomStayHistroy' => $currentCheckedInRoomStayHistroy
        ];
    }

    public function calculateTotalForHotelBranch($currentCheckedInRoomStayHistroy)
    {
        $totalDate = RoomServiceHelper::calculateCheckinDates($currentCheckedInRoomStayHistroy);
        // With Tax
        if ($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee) {
            $calculatedTotal = $currentCheckedInRoomStayHistroy->custom_rental_fee * $totalDate;
            // Tax
            $tax = RoomServiceHelper::calculateTax($currentCheckedInRoomStayHistroy, $this->settingService);
            $calculatedTotal += $tax;
        }else{
            $calculatedTotal =  $currentCheckedInRoomStayHistroy->rental_fee * $totalDate;
        }

        // Extra
        $calculatedTotal += $this->calculateExtra($currentCheckedInRoomStayHistroy->roomUtilityHistory);
        // Maintenance cost
        $calculatedTotal += $currentCheckedInRoomStayHistroy->room_maintenance_cost;
        // Deposit Other
        $calculatedTotal += $currentCheckedInRoomStayHistroy->deposit_other;
        // Deposit
        $calculatedTotal += $currentCheckedInRoomStayHistroy->deposit;
        // Keycard
        $calculatedTotal += $currentCheckedInRoomStayHistroy->deposit_keycard;
        // CASE : New logic (18/07/2020) , show discount each end of month
        $calculatedTotal -= $currentCheckedInRoomStayHistroy->discount;

        return [
            'calculatedTotal' => $calculatedTotal,
            'isIncludedCheckOut' => false,
            'currentCheckedInRoomStayHistroy' => $currentCheckedInRoomStayHistroy
        ];
    }

    private function calculateExtra($roomUtilityHistoryList)
    {
        $extraVal = 0;
        if ($roomUtilityHistoryList != null && sizeof($roomUtilityHistoryList) > 0) {
            foreach ($roomUtilityHistoryList as $item) {
                $setting = $this->settingService->getByStorageId($item->setting_id);
                if (!($setting->key == 'extra_2' || $setting->key == 'extra_5')) {
                    $extraVal += intval($setting->value);
                }
            }
        }
        return $extraVal;
    }

    public function calculateTaxHotel($currentCheckedInRoomStayHistory){
        $vat_special_property = $this->settingService->get('vat', 1);
        $calculatedTotal = $currentCheckedInRoomStayHistory->custom_rental_fee; // ดึงมาจาก custom rental fee
        $totalDate = RoomServiceHelper::calculateCheckinDates($currentCheckedInRoomStayHistory);
        $calculatedTotal = $calculatedTotal * $totalDate;
        return intval($calculatedTotal) * (floatval($vat_special_property) / 100);
    }

}