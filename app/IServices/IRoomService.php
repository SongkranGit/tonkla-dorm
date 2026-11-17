<?php

namespace App\IServices;

use Illuminate\Http\Request;

interface IRoomService
{

    /**
     * @param Illuminate\Http\Request $request
     *
     * @param int $id
     *
     * @return  bool
    **/     
    public function updateRoom(Request $request,$id); 

    /**
     * @param App\Models\RoomStayHistory $currentCheckedInRoomStayHistroy
     *
     * @return App\Models\RoomStayHistory
     *
    **/  
    public function calculateRoomDailyFee($currentCheckedInRoomStayHistroy ,$extras );

    /**
     * @param App\Models\RoomStayHistory $currentCheckedInRoomStayHistroy
     *
     * @param Array $extras
     *
     * @return App\Models\RoomStayHistory
     *
    **/  
    public function calculateRoomMonthlyFee($currentCheckedInRoomStayHistroy,$extras);

    /**
     * @param App\Models\RoomStayHistory $currentCheckedInRoomStayHistroy
     *
     * @param Request $request
     *
     * @return App\Models\RoomStayHistory
     *
    **/  
    public function getAttributeMapper($currentCheckedInRoomStayHistroy,Request $request) ;

    /**
     * @param string $actionType
     *
     * @param App\Models\Room $room 
     *
    **/     
    public function setActivityLog($actionType, $room); 

    public function generateReceiptRunningNumber();
}