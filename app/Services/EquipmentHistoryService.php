<?php

namespace App\Services;

use Auth;
use Illuminate\Http\Request;
use App\IServices\IEquipmentHistoryService;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

use App\Models\Equipment;
use App\Models\RoomEquipment;

class EquipmentHistoryService implements IEquipmentHistoryService
{

    public function getAllWithPagination(Request $request)
    {
        $itemSize = env('APP_ITEM_PER_PAGE', 15);
        $sortOrder = $request->get('SortOrder');
        $sortColumn = $request->get('SortColumn');
        $equipmentId = $request->get('EquipmentId'); 
        $lastMonth = Carbon::now()->subMonth()->toDateString(); 
        if($sortOrder != null || $sortColumn != null)
        {
            if($sortOrder == null)
            {
                $sortOrder = 'desc';
            }
            if($sortColumn == null)
            {
                $sortColumn = 'id';
            }
            $equipmentHistories = Activity::where('branch_id', session('current_branch_id'))
                ->where('subject_id','=',$equipmentId)
                ->where('subject_type','=','App\Models\Equipment')
                ->whereDate('created_at', '>=', $lastMonth)
                ->orderBy($sortColumn, $sortOrder)
                ->paginate($itemSize);
        }
        else
        {
            $equipmentHistories = Activity::where('branch_id', session('current_branch_id'))
                ->where('subject_id','=',$equipmentId)
                ->where('subject_type','=','App\Models\Equipment')
                ->whereDate('created_at', '>=', $lastMonth)
                ->paginate($itemSize);

        }   
        return $equipmentHistories;
    }

    public function updateEquipmentHistory(Request $request,$id) 
    {
        $equipmentHistory  = Activity::findOrFail($id);
        $equipment = json_decode($equipmentHistory->properties);
        if(isset($equipment->equipment))
        {
            $equipment = $equipment->equipment;
        }
        else
        {
            $equipment =  new Equipment();
        }

        $equipment->name = $request->name == null ?  $equipment->name : $request->name;
        $equipment->description = $request->description  ? $equipment->description : $request->description;

        if(Auth::user()->hasRole('admin')) 
        {
            $useQuantity = 0;
            $equipment->total_quantity = $request->total_quantity == null ?  $equipment->total_quantity : $request->total_quantity;
            if($equipment->room_equipment != null && sizeof($equipment->room_equipment) > 0)
            {     
                foreach($equipment->room_equipment as $roomEquipment) 
                {
                    $useQuantity += $roomEquipment->taken_quantity;
                }
                if($equipment->total_quantity > $useQuantity) { 
                    $equipment->available_quantity = $equipment->total_quantity - $useQuantity;
                } else {
                    $equipment->available_quantity = 0;
                }  
            }
            else 
            {
                $equipment->available_quantity = $equipment->total_quantity;
            }

        }

        $equipment->room_equipment = $this->manageRoomEquipment($request->roomEquipments);
        $equipmentHistory->properties = [ 'equipment' => $equipment];
        $equipmentHistory->save();

        return true;
    }

    private function manageRoomEquipment($requestRoomEquipments) 
    {
        $roomEquipments = [];
        if($requestRoomEquipments != null && sizeof($requestRoomEquipments) > 0)
        {
            foreach($requestRoomEquipments as $roomEquipment)
            {
                $roomEquipmentItem = new RoomEquipment();
                $roomEquipmentItem->taken_date = isset($roomEquipment['taken_date']) ?  Carbon::createFromFormat('d/m/Y H:i:s',$roomEquipment['taken_date'])->format('Y-m-d H:i:s')  : null;
                $roomEquipmentItem->id = isset($roomEquipment['id']) ? $roomEquipment['id'] : null; 
                $roomEquipmentItem->taken_quantity = isset($roomEquipment['taken_quantity']) ? $roomEquipment['taken_quantity'] : null; 
                $roomEquipmentItem->room_id = isset($roomEquipment['room_id']) ? $roomEquipment['room_id'] : null; 
                $roomEquipmentItem->equipment_id = isset($roomEquipment['equipment_id']) ? $roomEquipment['equipment_id'] : null;  
                $roomEquipmentItem->sort_no = isset($roomEquipment['sort_no']) ? $roomEquipment['sort_no'] : null;
                $roomEquipmentItem->created_at = isset($roomEquipment['created_at']) ? $roomEquipment['created_at'] : null;
                $roomEquipmentItem->updated_at = isset($roomEquipment['updated_at']) ? $roomEquipment['updated_at'] : null;
                array_push($roomEquipments,$roomEquipmentItem);
            }
        }
        return $roomEquipments;
    }

}