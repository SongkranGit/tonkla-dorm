<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\IServices\IRoomUtilityHistoryService; 
 
use App\Models\RoomUtilityHistory; 

class RoomUtilityHistoryService implements IRoomUtilityHistoryService
{
 
    public function upsertRoomUtilityHistories($roomUtilityHistories,$roomStayHistoryId)
    {
        if($roomStayHistoryId != null && $roomUtilityHistories != null && sizeof($roomUtilityHistories)) { 
            foreach($roomUtilityHistories as $roomUtilityHistory) {
                $roomUtilityHistoryItem = new RoomUtilityHistory();
                if(isset($roomUtilityHistory['id']) && $roomUtilityHistory['id'] > 0) {
                    $roomUtilityHistoryItem = RoomUtilityHistory::findOrFail($roomUtilityHistory['id']); 
                } 
                $roomUtilityHistoryItem->is_use = $roomUtilityHistory['is_use'] == 'true' || $roomUtilityHistory['is_use'] == true ? true : false;
                $roomUtilityHistoryItem->room_stay_history_id = $roomStayHistoryId;
                $roomUtilityHistoryItem->setting_id = $roomUtilityHistory['setting_id'];
                $roomUtilityHistoryItem->save();
            } 
            return true;
        }
        return false;
    }

    public function deleteRoomUtilityHistories($roomUtilityHistories)
    { 
        if($roomUtilityHistories != null && sizeof($roomUtilityHistories) > 0) {
            foreach($roomUtilityHistories as $roomUtilityHistory) {  
                if(isset($roomUtilityHistory['id']) && $roomUtilityHistory['id'] > 0) {
                    $roomUtilityHistoryItem = RoomUtilityHistory::findOrFail($roomUtilityHistory['id']); 
                    $roomUtilityHistoryItem->delete();
                }  
            }
            return true;
        }
        return false;
    }
}