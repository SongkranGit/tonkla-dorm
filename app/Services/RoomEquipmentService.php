<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\IServices\IRoomEquipmentService;
use Carbon\Carbon;

use App\Models\Equipment;
use App\Models\RoomEquipment;

class RoomEquipmentService implements IRoomEquipmentService
{
      
    public function upsertRoomEquipments($roomEquipments,$equipmentId)
    { 
        if( $equipmentId != null && $roomEquipments != null && sizeof($roomEquipments) > 0) {
            foreach($roomEquipments as $roomEquipment) {
                $roomEquipmentItem = new RoomEquipment();
                $equipmentItem = Equipment::findOrFail($equipmentId);
                $takenQuantity = 0;
                if(isset($roomEquipment['id']) && $roomEquipment['id'] > 0) 
                {
                    $roomEquipmentItem = RoomEquipment::findOrFail($roomEquipment['id']); 
                    $takenQuantity = $roomEquipment['taken_quantity'];
                    if ($roomEquipment['taken_quantity'] > 0  
                    && $roomEquipment['taken_quantity'] < $roomEquipmentItem->taken_quantity) 
                    {  
                        $equipmentItem->available_quantity += $roomEquipmentItem->taken_quantity - $roomEquipment['taken_quantity'];
                    } 
                    else if ($roomEquipment['taken_quantity'] > 0 
                    && $roomEquipment['taken_quantity'] > $roomEquipmentItem->taken_quantity 
                    && $equipmentItem->available_quantity + $roomEquipmentItem->taken_quantity - $roomEquipment['taken_quantity'] >= 0) 
                    { 
                        $equipmentItem->available_quantity += $roomEquipmentItem->taken_quantity;
                        $equipmentItem->available_quantity -= $roomEquipment['taken_quantity'];
                    }
                } 
                else
                {
                    if($roomEquipment['taken_quantity'] > 0 
                        && $equipmentItem->available_quantity - $roomEquipment['taken_quantity'] >= 0) 
                    {
                        $equipmentItem->available_quantity -= $roomEquipment['taken_quantity'];
                        $takenQuantity = $roomEquipment['taken_quantity'];
                    } 
                }
                $equipmentItem->save();
        
                $roomEquipmentItem->taken_quantity = $takenQuantity;
                $roomEquipmentItem->equipment_id = $equipmentId;
                $roomEquipmentItem->room_id = $roomEquipment['room_id']; 
                $roomEquipmentItem->taken_date = $roomEquipment['taken_date'] != null ?  Carbon::createFromFormat('d/m/Y H:i:s',$roomEquipment['taken_date'])->format('Y-m-d H:i:s')  : null;
                $roomEquipmentItem->sort_no = $roomEquipment['sort_no'];
                $roomEquipmentItem->save();
            }
            return true;
        }
        return false;
    }
      
    public function deleteRoomEquipments($roomEquipments)
    {  
        if($roomEquipments != null && sizeof($roomEquipments) > 0) {
            foreach($roomEquipments as $roomEquipment) {  
                if(isset($roomEquipment['id']) && $roomEquipment['id'] > 0) {
                    $roomEquipmentItem = RoomEquipment::findOrFail($roomEquipment['id']);  
                    $equipmentItem = Equipment::findOrFail($roomEquipmentItem->equipment_id);
                    $equipmentItem->available_quantity += $roomEquipmentItem->taken_quantity;
                    $equipmentItem->save();
                    $roomEquipmentItem->delete();
                }  
            }
            return true;
        }  
        return false;
    }

}