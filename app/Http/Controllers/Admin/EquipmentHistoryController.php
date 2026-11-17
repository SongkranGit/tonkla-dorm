<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;

use Response;
use Redirect;

use App\Models\Room;
use App\Models\Equipment;
use App\Models\EquipmentHistory;

use App\Http\Requests\Admin\Equipment\EquipmentHistoryUpdateRequest; 

use App\IServices\IEquipmentHistoryService;

class EquipmentHistoryController extends Controller
{ 
    private $equipmentHistoryService;

    public function __construct( 
        IEquipmentHistoryService $equipmentHistoryService
    )
    {
        $this->middleware(['auth','role:admin|owner']);
        $this->equipmentHistoryService = $equipmentHistoryService;
    }
 
    public function index(Request $request)
    {
        $equipmentId = $request->get('EquipmentId'); 
        if($equipmentId != null) 
        {
            $equipment = Equipment::findOrFail($equipmentId);
            $equipmentHistories = $this->equipmentHistoryService->getAllWithPagination($request);
            return view('admin.equipment_history.index',[
                'equipmentHistories' => $equipmentHistories,
                'equipment' => $equipment,
            ]);
        } 
        return Redirect('equipment'); 
    }
 
    public function edit($id)
    {
        $rooms = Room::where('branch_id' , session('current_branch_id'))->orderBy('id')->get();
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
        return view('admin.equipment_history.edit',[ 
            'rooms'=> $rooms,
            'equipmentHistory' => $equipmentHistory,
            'equipment' => $equipment,
        ]);
    }
 
    public function update(EquipmentHistoryUpdateRequest $request, $id)
    { 
        $equipmentHistory  = Activity::findOrFail($id,['subject_id','subject_type']);
        if($equipmentHistory->subject_type == 'App\Models\Equipment')
        {
            $this->equipmentHistoryService->updateEquipmentHistory($request, $id);
            return Redirect('/admin/equipmenthistory?EquipmentId='. $equipmentHistory->subject_id); 
        }
        abort(404);
    }

    public function destroy($id)
    {
       $roomHistory  = Activity::findOrFail($id);
       $roomHistory->delete();
       return Response::json();
    }

}
