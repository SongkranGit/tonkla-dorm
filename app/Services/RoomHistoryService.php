<?php

namespace App\Services;

use App\Helpers\RoomServiceHelper;
use Illuminate\Http\Request;
use App\IServices\IRoomHistoryService; 
use App\IServices\IRoomService;

use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use App\Models\Keycard;
use App\Models\RoomStayHistory;
use App\Models\RoomUtilityHistory;

class RoomHistoryService implements IRoomHistoryService
{
    
    private $roomService;

    public function __construct(
        IRoomService $roomService
    )
    {
        $this->roomService = $roomService;
    }

    public function getAllWithPagination(Request $request)
    {
        $itemSize = env('APP_ITEM_PER_PAGE', 15);
        $sortOrder = $request->get('SortOrder');
        $sortColumn = $request->get('SortColumn');
        $roomId = $request->get('RoomId');
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
            $roomHistories = Activity::where('branch_id', session('current_branch_id'))
                ->where('subject_id','=',$roomId)
                ->where('subject_type','=','App\Models\Room')
                ->orderBy($sortColumn, $sortOrder)
                ->paginate($itemSize);
        }
        else
        {
            $roomHistories = Activity::where('branch_id', session('current_branch_id'))
                ->where('subject_id','=',$roomId)
                ->where('subject_type','=','App\Models\Room')
                ->orderBy('id', 'desc')
                ->paginate($itemSize);
        }   
        return $roomHistories;
    }

    public function updateRoomHistory(Request $request,$id) 
    {
        $roomHistoryActivity  = Activity::findOrFail($id);  
        $roomHistory = json_decode($roomHistoryActivity->properties); 

        if(!isset($roomHistory->currentCheckedInRoomStayHistroy)) 
        { 
            $roomStayHistory = new RoomStayHistory();
            $roomHistory->currentCheckedInRoomStayHistroy = $this->getAttributeMapper($roomStayHistory, $request);
        }

        if ($request->status == 'available' && $roomHistory->room->status != 'available') 
        {        
            $roomHistory->room->status = 'available';  
            if ($roomHistory->currentCheckedInRoomStayHistroy != null) { 
                $roomHistory->currentCheckedInRoomStayHistroy->status = 'checked_out'; 
            }
            $roomHistory->room->keycards = [];
        } 
        else 
        {  
           
            $roomHistory->currentCheckedInRoomStayHistroy->status = 'checked_in'; 

            $roomHistory->currentCheckedInRoomStayHistroy = $this->getAttributeMapper($roomHistory->currentCheckedInRoomStayHistroy, $request);
            $roomHistory->currentCheckedInRoomStayHistroy->check_in = $request->check_in;
            $roomHistory->currentCheckedInRoomStayHistroy->check_out = $request->check_out;

            if (RoomServiceHelper::checkRoomTypeDaily($request->status))
            {
                $roomHistory->room->status = $request->status;

                $roomHistory->currentCheckedInRoomStayHistroy = $this->roomService->calculateRoomDailyFee($roomHistory->currentCheckedInRoomStayHistroy);
            
                //Extras
                $roomHistory->currentCheckedInRoomStayHistroy->room_utility_history = [];
            } 
            elseif ($request->status == 'monthly_air' || $request->status == 'monthly_fan'  || $request->status == 'monthly_air_2' || $request->status == 'monthly_fan_2'  || $request->status == 'monthly_house' ) 
            {
                $roomHistory->room->status = $request->status;

                $roomHistory->currentCheckedInRoomStayHistroy = $this->roomService->calculateRoomMonthlyFee($roomHistory->currentCheckedInRoomStayHistroy,$request->extras);
                
                //Extras
                $roomHistory->currentCheckedInRoomStayHistroy->room_utility_history = $this->manageRoomUtilityHistories($request->extras);
            } 
            
            //Keycards
            $roomHistory->room->keycards = $this->manageKeycards($request->keycards);
            
        }
        
        $roomHistoryActivity->properties = $roomHistory;
        $roomHistoryActivity->save();

        return true;
    }

    private function manageKeycards($requestKeycards) 
    {
        $keycards = [];
        if($requestKeycards != null && sizeof($requestKeycards) > 0)
        {
            foreach($requestKeycards as $keycard)
            {
                $keycardItem = new Keycard();
                $keycardItem->taken_date = isset($keycard['taken_date']) ?  Carbon::createFromFormat('d/m/Y H:i:s',$keycard['taken_date'])->format('Y-m-d H:i:s')  : null;
                $keycardItem->id = isset($keycard['id']) ? $keycard['id'] : null;
                $keycardItem->key_card_no = isset($keycard['key_card_no']) ? $keycard['key_card_no'] : null;
                $keycardItem->sort_no = isset($keycard['sort_no']) ? $keycard['sort_no'] : null;
                $keycardItem->created_at = isset($keycard['created_at']) ? $keycard['created_at'] : null;
                $keycardItem->updated_at = isset($keycard['updated_at']) ? $keycard['updated_at'] : null;
                array_push($keycards,$keycardItem);
            }
        }
        return $keycards;
    }

    private function manageRoomUtilityHistories($requestExtras) 
    {
        $roomUtilityHistories = [];
        if($requestExtras != null && sizeof($requestExtras) > 0)
        {
            foreach($requestExtras as $extra)
            {
                $extraItem = new RoomUtilityHistory();
                $extraItem->id = isset($extra['id']) ? $extra['id'] : null;
                $extraItem->is_use = isset($extra['is_use']) ? ($extra['is_use'] == true || $extra['is_use'] == 'true' ? true : false) : null;
                $extraItem->room_stay_history_id = isset($extra['room_stay_history_id']) ? $extra['room_stay_history_id'] : null;
                $extraItem->setting_id = isset($extra['setting_id']) ?  $extra['setting_id'] : null;
                $extraItem->appSettingEloquentStorage;
                array_push($roomUtilityHistories,$extraItem);
            }
        }
        return $roomUtilityHistories;
    }

    private function getAttributeMapper($currentCheckedInRoomStayHistroy,Request $request) 
    {
        $currentCheckedInRoomStayHistroy->renter_firstname = $request->renter_firstname !=null ? $request->renter_firstname: '';
        $currentCheckedInRoomStayHistroy->renter_lastname = $request->renter_lastname  !=null? $request->renter_lastname: '';
        $currentCheckedInRoomStayHistroy->renter_telephone = $request->renter_telephone !=null? $request->renter_telephone: '';
        
        $currentCheckedInRoomStayHistroy->renter_id_card_no = $request->renter_id_card_no  !=null?$request->renter_id_card_no  : '';
        $currentCheckedInRoomStayHistroy->renter_tax_payer_no = $request->renter_tax_payer_no  !=null? $request->renter_tax_payer_no: ''; 
        $currentCheckedInRoomStayHistroy->renter_current_address = $request->renter_current_address  !=null ?  $request->renter_current_address: '';
        
        $currentCheckedInRoomStayHistroy->check_in = $request->check_in != null ? Carbon::createFromFormat('d/m/Y H:i:s',$request->check_in)->format('Y-m-d H:i:s') : '';
        $currentCheckedInRoomStayHistroy->check_out = $request->check_out != null ? Carbon::createFromFormat('d/m/Y H:i:s',$request->check_out)->format('Y-m-d H:i:s') : '';

        $currentCheckedInRoomStayHistroy->deposit = $request->deposit != null ? $request->deposit : ''; 
        $currentCheckedInRoomStayHistroy->deposit_keycard = $request->deposit_keycard  != null ? $request->deposit_keycard : '';
        $currentCheckedInRoomStayHistroy->deposit_other = $request->deposit_other  != null ? $request->deposit_other : '';

        $currentCheckedInRoomStayHistroy->rental_fee = $request->rental_fee  != null? $request->rental_fee: '';
        $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee = $request->is_use_custom_rental_fee == 'true' ?  true : false;
        $currentCheckedInRoomStayHistroy->custom_rental_fee = $request->custom_rental_fee != null?  $request->custom_rental_fee: '' ;
        $currentCheckedInRoomStayHistroy->discount = $request->discount != null? $request->discount : '' ;

        $currentCheckedInRoomStayHistroy->discount_remark = $request->discount_remark != null? $request->discount_remark: '' ;

        $currentCheckedInRoomStayHistroy->electric_meter_start ='';
        $currentCheckedInRoomStayHistroy->electric_meter_end ='';
        $currentCheckedInRoomStayHistroy->electric_meter_unit ='';
        $currentCheckedInRoomStayHistroy->electric_meter_fee ='';
        $currentCheckedInRoomStayHistroy->water_meter_start ='';
        $currentCheckedInRoomStayHistroy->water_meter_end ='';
        $currentCheckedInRoomStayHistroy->water_meter_unit ='';
        $currentCheckedInRoomStayHistroy->water_meter_fee ='';

        $currentCheckedInRoomStayHistroy->renter_school = '';
        $currentCheckedInRoomStayHistroy->renter_school_class = '';
        $currentCheckedInRoomStayHistroy->renter_parent_firstname = '';
        $currentCheckedInRoomStayHistroy->renter_parent_lastname = '';
        $currentCheckedInRoomStayHistroy->renter_parent_id_card_no = '';
        $currentCheckedInRoomStayHistroy->renter_parent_telephone = '';

        $currentCheckedInRoomStayHistroy->electric_meter_start = $request->electric_meter_start  != null ? $request->electric_meter_start : '';
        $currentCheckedInRoomStayHistroy->electric_meter_end = $request->electric_meter_end  != null ?  $request->electric_meter_end: '';
        $currentCheckedInRoomStayHistroy->electric_meter_unit ='';
        $currentCheckedInRoomStayHistroy->electric_meter_fee ='';

        $currentCheckedInRoomStayHistroy->water_meter_start = $request->water_meter_start  != null ? $request->water_meter_start : '';
        $currentCheckedInRoomStayHistroy->water_meter_end = $request->water_meter_end  != null ? $request->water_meter_end  : '';
        $currentCheckedInRoomStayHistroy->water_meter_unit ='';
        $currentCheckedInRoomStayHistroy->water_meter_fee ='';
        
        $currentCheckedInRoomStayHistroy->renter_school = $request->renter_school  != null ?  $request->renter_school : '';
        $currentCheckedInRoomStayHistroy->renter_school_class = $request->renter_school_class  != null ? $request->renter_school_class : '';
        $currentCheckedInRoomStayHistroy->renter_parent_firstname = $request->renter_parent_firstname  != null ? $request->renter_parent_firstname : '';
        $currentCheckedInRoomStayHistroy->renter_parent_lastname = $request->renter_parent_lastname  != null ? $request->renter_parent_lastname : '';
        $currentCheckedInRoomStayHistroy->renter_parent_id_card_no = $request->renter_parent_id_card_no  != null ? $request->renter_parent_id_card_no: '';
        $currentCheckedInRoomStayHistroy->renter_parent_telephone = $request->renter_parent_telephone  != null ? $request->renter_parent_telephone  : '';

        return $currentCheckedInRoomStayHistroy;
    } 

}