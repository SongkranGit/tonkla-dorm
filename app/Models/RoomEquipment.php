<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RoomEquipment extends Model
{
   /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

        protected $table = 'room_equipment';
        protected $primaryKey = 'id';

        // public $timestamps = false;
        // protected $guarded = ['id'];

        protected $fillable = [
            'sort_no',
            'taken_quantity',
            'room_id',
            'taken_date',
           
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

        /**
        * The accessors to append to the model's array form.
        *
        * @var array
        */
        protected $appends = [
            'taken_quantity_show',
        ];
    
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
    // public function room()
    // {
    //     return $this->belongsToMany('App\Models\Room','room_equipment','room_id','equipment_id')->withTimestamps();
    // }

    public function equipment()
    {
        return $this->belongsTo('App\Models\Equipment');
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
    public function getTakenDateAttribute($value)
    {
        $result = '';
        if(!is_null($value))
        {
            $result = Carbon::parse($value)->format('d/m/Y H:i:s');
        }
        return $result;
    }
    public function getTakenQuantityShowAttribute()
    {
        return number_format($this->taken_quantity,0);
    }    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
