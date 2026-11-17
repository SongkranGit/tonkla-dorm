<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomUtilityHistory extends Model
{
    /*
        |--------------------------------------------------------------------------
        | GLOBAL VARIABLES
        |--------------------------------------------------------------------------
        */
            
        protected $table = 'room_utility_histories';
        protected $primaryKey = 'id';

        // public $timestamps = false;
        // protected $guarded = ['id'];
        
        protected $fillable = [
            'setting_id',
            'room_stay_history_id',
            'is_use',
        ];

        // protected $hidden = [];
        // protected $dates = [
        // 	'check_in',
        //     'check_out',
        // ];

        protected $casts = [
            'is_use' => 'boolean',
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
    
        public function roomStayHistory()
        {
            return $this->belongsTo('App\Models\RoomStayHistory');
        } 
    
    
        public function appSettingEloquentStorage()
        {
            return $this->belongsTo('App\Models\AppSettingEloquentStorage','setting_id','id');
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
