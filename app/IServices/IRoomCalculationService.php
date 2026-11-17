<?php

namespace App\IServices;

use Illuminate\Http\Request;

interface IRoomCalculationService
{
    public function calculatePrintCheckin($currentCheckedInRoomStayHistroy);

    public function calculatePrintCheckout($currentCheckedInRoomStayHistroy, $id);

    public function calculatePrintCheckoutForHotel($currentCheckedInRoomStayHistroy, $id);

    public function calculateTotal($currentCheckedInRoomStayHistroy);

    public function calculateTotalForHotelBranch($currentCheckedInRoomStayHistroy);

    public function calculateTaxHotel($currentCheckedInRoomStayHistroy);
}