<?php

namespace App\IServices;

use Illuminate\Http\Request;

interface IRoomUtilityHistoryService
{

    /**
     * @param array $roomUtilityHistories
     *
     * @param int $roomStayHistoryId
     *
     * @return  bool
    **/     
    public function upsertRoomUtilityHistories($roomUtilityHistories,$roomStayHistoryId);  

    /**
     * @param array $roomUtilityHistories
     * 
     *
     * @return  bool
    **/     
    public function deleteRoomUtilityHistories($keycards);  

}