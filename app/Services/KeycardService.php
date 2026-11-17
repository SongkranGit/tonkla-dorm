<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\IServices\IKeycardService;
use Carbon\Carbon;

use App\Models\Keycard;

class KeycardService implements IKeycardService
{
      
    public function upsertKeycards($keycards,$roomId)
    { 
        if($roomId != null && $keycards != null && sizeof($keycards) > 0) {
            foreach($keycards as $keycard) {
                $keycardItem = new Keycard();
                if(isset($keycard['id']) && $keycard['id'] > 0) {
                    $keycardItem = Keycard::findOrFail($keycard['id']); 
                } 
                $keycardItem->room_id = $roomId;
                $keycardItem->key_card_no = $keycard['key_card_no']; 
                $keycardItem->taken_date = $keycard['taken_date'] != null ?  Carbon::createFromFormat('d/m/Y H:i:s',$keycard['taken_date'])->format('Y-m-d H:i:s')  : null;
                $keycardItem->sort_no = $keycard['sort_no'];
                $keycardItem->save();
            }
            return true;
        }
        return false;
    }
      
    public function deleteKeycards($keycards)
    { 
        if($keycards != null && sizeof($keycards) > 0) {
            foreach($keycards as $keycard) {  
                if(isset($keycard['id']) && $keycard['id'] > 0) {
                    $keycardItem = Keycard::findOrFail($keycard['id']); 
                    $keycardItem->delete();
                }  
            }
            return true;
        }
        return false;
    }

}