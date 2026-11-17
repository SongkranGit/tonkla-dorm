<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model 
{
    /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
        
        protected $table = 'rooms';
        protected $primaryKey = 'id';

        // public $timestamps = false;
        // protected $guarded = ['id'];
        
        protected $fillable = [
            'room_no', 
            'status', 
            'sort_order',
            'is_checkin_approved',
            'is_checkout_approved',
            'is_request_for_approve',
        ];

        // protected $hidden = [];
        // protected $dates = [
		// 	'check_in',
        //     'check_out',
		// ];

		// protected $casts = [
		// 	'check_in' => 'datetime',
		// 	'check_out' => 'datetime',
		// ];
     /*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/ 
        public function getCurrentCheckedInRoomStayHistroy() 
        {
            return $this->roomStayHistories()
                    ->where('status', 'checked_in') 
                    ->latest()
                    ->first();
        }

        public function getCheckedInRoomStayHistroies($startDate,$endDate,$isIncludeCustomRentalFee) 
        { 
            if($isIncludeCustomRentalFee) 
            {
                return $this->roomStayHistories()
                    ->select(['id','status','discount','deposit','deposit_keycard','deposit_other','total_price','created_at','updated_at'])
                    ->where('updated_at', '>=', $startDate) 
                    ->where('updated_at', '<=', $endDate)  
                    // ->where('status', 'checked_out') 
                    ->where('is_moved', false)  
                    ->get();
            }
            else 
            {
                return $this->roomStayHistories()
                    ->select(['id','status','discount','deposit','deposit_keycard','deposit_other','total_price','created_at','updated_at'])
                    ->where('updated_at', '>=', $startDate) 
                    ->where('updated_at', '<=', $endDate)   
                    // ->where('status', 'checked_out') 
                    ->where('is_moved', false) 
                    ->where('is_use_custom_rental_fee', false) 
                    ->get();
            }
        }
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/

        public function keycards()
        {
            return $this->hasMany('App\Models\Keycard');
        }

        public function roomStayHistories()
        {
            return $this->hasMany('App\Models\RoomStayHistory');
        }

        public function equipments()
        {
            return $this->belongsToMany('App\Models\Equipment','room_equipment','room_id','equipment_id')->withTimestamps();
        }

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| ACCESORS
	|--------------------------------------------------------------------------
	*/
	
	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
}
