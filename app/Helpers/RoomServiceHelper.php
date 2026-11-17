<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RoomServiceHelper
{
    public static function isRoomTypeDaily($currentCheckedInRoomStayHistory)
    {
        if ($currentCheckedInRoomStayHistory->room->status == 'daily' || $currentCheckedInRoomStayHistory->room->status == 'daily_2' || $currentCheckedInRoomStayHistory->room->status == 'daily_3') {
            return true;
        }
        return false;
    }

    public static function checkRoomTypeDaily($roomStatus)
    {
        if ($roomStatus == 'daily' || $roomStatus == 'daily_2' || $roomStatus == 'daily_3') {
            return true;
        }
        return false;
    }

    public static function calculateRoomRateForRoomTypeDaily($currentCheckedInRoomStayHistory)
    {
        if (self::isRoomTypeDaily($currentCheckedInRoomStayHistory)) {
            if (isset($currentCheckedInRoomStayHistory->check_out) && isset($currentCheckedInRoomStayHistory->check_in)
                && $currentCheckedInRoomStayHistory->check_out != null
                && $currentCheckedInRoomStayHistory->check_in != null) {
                $totalDate = Carbon::createFromFormat('d/m/Y H:i:s', $currentCheckedInRoomStayHistory->check_out)
                    ->startOfDay()
                    ->diffInDays(Carbon::createFromFormat('d/m/Y H:i:s', $currentCheckedInRoomStayHistory->check_in)->startOfDay());
                return self::calculateRoomRate($currentCheckedInRoomStayHistory, $totalDate);
            } else {
                return self::calculateRoomRate($currentCheckedInRoomStayHistory, null);
            }
        } else {
            return 0;
        }
    }

    public static function calculateTotal($currentCheckedInRoomStayHistory )
    {
        $discount = $currentCheckedInRoomStayHistory->discount;
        $keyCard = $currentCheckedInRoomStayHistory->deposit_keycard;
        return number_format(($discount - $keyCard), 2);
    }

    public static function calculateTotalWithTax($currentCheckedInRoomStayHistory, $totalPayTax)
    {
        $discount = $currentCheckedInRoomStayHistory->discount;
        $calculateTotal = ($currentCheckedInRoomStayHistory->custom_rental_fee + $totalPayTax) - $discount ;
        return number_format($calculateTotal, 2);
    }


    public static function calculateTotalForHotelBranch($currentCheckedInRoomStayHistory )
    {
        $discount = $currentCheckedInRoomStayHistory->discount;
        $keyCard = $currentCheckedInRoomStayHistory->deposit_keycard;
        return number_format(($discount - $keyCard), 2);
    }

    public static function calculateTotalWithTaxForHotelBranch($currentCheckedInRoomStayHistory, $totalPayTax)
    {
        $calculateTotal = ( $currentCheckedInRoomStayHistory->custom_rental_fee * self::calculateCheckinDates($currentCheckedInRoomStayHistory) + $totalPayTax);
        if($currentCheckedInRoomStayHistory->discount != null && $currentCheckedInRoomStayHistory->discount > 0){
            $calculateTotal -= $currentCheckedInRoomStayHistory->discount;
        }
        return number_format($calculateTotal, 2);
    }

    private static function calculateRoomRate($currentCheckedInRoomStayHistory, $totalDate)
    {
        if ($totalDate != null) {
            if (isset($currentCheckedInRoomStayHistory->is_use_custom_rental_fee)
                && $currentCheckedInRoomStayHistory->is_use_custom_rental_fee == 1) {
                $roomRate = number_format($currentCheckedInRoomStayHistory->custom_rental_fee * $totalDate, 2);
            } else {
                $roomRate = number_format($currentCheckedInRoomStayHistory->rental_fee * $totalDate, 2);
            }
        } else {
            if (isset($currentCheckedInRoomStayHistory->is_use_custom_rental_fee)
                && $currentCheckedInRoomStayHistory->is_use_custom_rental_fee == 1) {
                $roomRate = number_format($currentCheckedInRoomStayHistory->custom_rental_fee, 2);
            } else {
                $roomRate = number_format($currentCheckedInRoomStayHistory->rental_fee, 2);
            }
        }

        return $roomRate;
    }

    public static function calculateTax($currentCheckedInRoomStayHistory , $settingService){
        $tax = 0 ;
        $customRentalFree = floatval($currentCheckedInRoomStayHistory->custom_rental_fee);
        $rentalFree = floatval($currentCheckedInRoomStayHistory->rental_fee);
        // calculate for daily (default 7%)
        if(self::isRoomTypeDaily($currentCheckedInRoomStayHistory)){
            $vat = $settingService->get('vat', 1);
            $tax =( $customRentalFree * self::calculateCheckinDates($currentCheckedInRoomStayHistory) ) * (floatval($vat) / 100);
        }else{
            // calculate for monthly (default 12%)
            $vat = $settingService->get('vat_special_property', 1);
            // Case : เบิกภาษีแต่ไม่มีการเปลี่ยนแปลงค่าห้อง (input field)
            if ($customRentalFree === $rentalFree) {
                $furnitureCost = self::getFurnitureCost($currentCheckedInRoomStayHistory->roomUtilityHistory, $settingService);
                $roomCost = $customRentalFree + $furnitureCost;
                $diffPay = $roomCost - $customRentalFree;
            } else {
                $diffPay = $customRentalFree - $rentalFree;
            }
            if ($diffPay > 0) {
                $tax = $diffPay * (floatval($vat) / 100);
            }
        }
        return $tax;
    }

    public static function getFurnitureCost($roomUtilityHistoryList , $settingService ){
        $furnitureCost = 0;
        if ($roomUtilityHistoryList != null && sizeof($roomUtilityHistoryList) > 0) {
            foreach ($roomUtilityHistoryList as $item) {
                $setting = $settingService->getByStorageId($item->setting_id);
                if ($setting->key == 'extra_2') {
                    $furnitureCost = intval($setting->value);
                    break;
                }
            }
        }
        return $furnitureCost;
    }

    public static function getRoomRateIncludedFurniture($currentCheckedInRoomStayHistroy , $settingService){
        $furnitureCost = self::getFurnitureCost($currentCheckedInRoomStayHistroy->roomUtilityHistory , $settingService);
        $roomRate = $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ? $currentCheckedInRoomStayHistroy->custom_rental_fee : $currentCheckedInRoomStayHistroy->rental_fee ;
        $roomRateIncludeFurniture = $roomRate + $furnitureCost;
        return number_format($roomRateIncludeFurniture, 2);
    }

    public static function getRoomRate($currentCheckedInRoomStayHistroy, $settingService){
        if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee){
            $roomRate = $currentCheckedInRoomStayHistroy->custom_rental_fee ;
        }else{
            $roomRate = $currentCheckedInRoomStayHistroy->rental_fee;
        }
        return number_format($roomRate, 2);
    }

    public static function calculateCheckinDates($currentCheckedInRoomStayHistroy){
        $totalDate = Carbon::createFromFormat('d/m/Y H:i:s', $currentCheckedInRoomStayHistroy->check_out)->startOfDay()->diffInDays(Carbon::createFromFormat('d/m/Y H:i:s', $currentCheckedInRoomStayHistroy->check_in)->startOfDay());
        return $totalDate != null ?  $totalDate : 1;
    }

    public static function getExtraNameEng($key){
        $extraName = "";
        switch ($key){
            case "extra_1" :  $extraName = "Internet" ; break;
            case "extra_2" :  $extraName = "Furniture" ; break;
            case "extra_3" :  $extraName = "TV" ; break;
            case "extra_4" :  $extraName = "Refrigerator" ; break;
            case "extra_5" :  $extraName = "Cleaning fee" ; break;
            case "extra_6" :  $extraName = "Refrigerator small" ; break;
            case "extra_7" :  $extraName = "Internet" ; break;
            case "extra_8" :  $extraName = "Fines to pay the room after the scheduled time" ; break;
        }

        return $extraName;
    }

    public static function getMonthlyRoomRate($currentCheckedInRoomStayHistroy, $settingService){
        if ($currentCheckedInRoomStayHistroy->custom_rental_fee === $currentCheckedInRoomStayHistroy->rental_fee) {
            $furnitureCost = RoomServiceHelper::getFurnitureCost($currentCheckedInRoomStayHistroy->roomUtilityHistory, $settingService);
            $monthlyRoomRate = $currentCheckedInRoomStayHistroy->rental_fee + $furnitureCost;
        }else{
            $monthlyRoomRate = $currentCheckedInRoomStayHistroy->custom_rental_fee;
        }

        return number_format($monthlyRoomRate , 2);
    }

}