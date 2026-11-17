<?php

namespace App\Services;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\IServices\IEquipmentService;
use App\Iservices\IRoomEquipmentService;
use App\Iservices\IRoomService;

use Illuminate\Contracts\Cache\Factory as CacheContract;
use Spatie\Activitylog\Models\Activity;

use App\Models\Equipment;
use App\Models\RoomEquipment;
use App\Models\Room;

class EquipmentService implements IEquipmentService
{
    
    private $roomEquipmentService;
    private $roomService;
    
    public function __construct(
        IRoomEquipmentService $roomEquipmentService,
        IRoomService $roomService)
    { 
        $this->roomEquipmentService = $roomEquipmentService;
        $this->roomService = $roomService;
    } 
    
    public function getAllEquipmentWithPagination(Request $request) 
    {
        $itemSize = env('APP_ITEM_PER_PAGE', 15);
        $sortOrder = $request->get('SortOrder');
        $sortColumn = $request->get('SortColumn');
        if($sortOrder != null || $sortColumn != null) 
        {
            if($sortOrder == null) 
            {
                $sortOrder = 'desc';
            }
            if($sortColumn == null) 
            {
                $sortColumn = 'equipment_no';
            }
            $equipments = Equipment::where('branch_id' , session('current_branch_id'))->orderBy($sortColumn, $sortOrder)->paginate($itemSize);
        } 
        else 
        {
            $equipments = Equipment::where('branch_id' , session('current_branch_id'))->paginate($itemSize);
        }
        return $equipments;
    }

    public function create(Request $request)
    {
        $equipment = new Equipment(); 
        $equipment->equipment_no = $request->equipment_no;
        $equipment->name = $request->name;
        $equipment->description = $request->description;
        $equipment->total_quantity = $request->total_quantity;
        $equipment->available_quantity = $request->total_quantity;
        $equipment->branch_id = session('current_branch_id');
        $equipment->save();
        return $equipment;
    }  

    public function updateEquipment(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $this->setActivityLog('equipment_updated', $equipment);
        $equipment->name = $request->name == null ?  $equipment->name : $request->name;
        $equipment->description = $request->description  ? $equipment->description : $request->description;
        $equipment->branch_id = session('current_branch_id');

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
        
        $equipment->save();

        //Room Equipment
        $this->roomEquipmentService->deleteRoomEquipments($request->delete_roomEquipments);
        $this->roomEquipmentService->upsertRoomEquipments($request->roomEquipments, $equipment->id);
         
        return true;
    }

    public function setActivityLog($actionType, $equipment, $description = "")
    {
        $equipment->room_equipment;
        $properties = [
            'equipment' => $equipment,
        ];
        activity($actionType)
        ->causedBy(\Auth::user())
        ->performedOn($equipment)
        ->withProperties($properties)
        ->tap(function(Activity $activity) {
            $activity->branch_id = session('current_branch_id');
        })
        ->log($description);
    }
    

}