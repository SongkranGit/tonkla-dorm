<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RoomStayHistory extends Model
{
   /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'room_stay_histories';
    protected $primaryKey = 'id';

    // public $timestamps = false;
    // protected $guarded = ['id'];

    protected $fillable = [ 
        'status',
        'check_in',
        'check_out',
        'renter_firstname',
        'renter_lastname',
        'renter_telephone',
        'renter_id_card_no',
        'renter_tax_payer_no',
        'renter_school',
        'renter_school_class',
        'renter_parent_firstname',
        'renter_parent_lastname',
        'renter_parent_id_card_no',
        'renter_parent_telephone',
        'renter_current_address',
        'deposit',
        'deposit_keycard',
        'deposit_other',
        'rental_fee',
        'discount',
        'discount_remark',
        'electric_meter_start',
        'electric_meter_end',
        'electric_meter_unit',
        'electric_meter_fee',
        'water_meter_start',
        'water_meter_end',
        'water_meter_unit',
        'water_meter_fee',
        'room_maintenance_cost',
        'total_price',
        'custom_rental_fee',
        'is_use_custom_rental_fee',
        'is_moved',
        'paid_date',
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

        public function room()
        {
            return $this->belongsTo('App\Models\Room');
        }

        public function roomUtilityHistory()
        {
            return $this->hasMany('App\Models\RoomUtilityHistory');
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

        public function getCheckInAttribute($value)
        {
            $result = '';
            if(!is_null($value))
            {
                $result = Carbon::parse($value)->format('d/m/Y H:i:s');
            }
            return $result;
        }

        public function getCheckOutAttribute($value)
        {
            $result = '';
            if(!is_null($value))
            {
                $result = Carbon::parse($value)->format('d/m/Y H:i:s');
            }
            return $result;
        }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
