<?php

namespace App\Models;

use Unisharp\Setting\EloquentStorage;
use Illuminate\Database\Eloquent\Model as Eloquent;

class AppSettingEloquentStorage extends EloquentStorage
{
        /*
        |--------------------------------------------------------------------------
        | GLOBAL VARIABLES
        |--------------------------------------------------------------------------
        */
            
        // protected $table = 'keycards';
        // protected $primaryKey = 'id';

        // public $timestamps = false;
        // protected $guarded = ['id'];
        
        protected $fillable = [
            'branch_id',
            'key', 
            'value', 
            'locale',
            'extra_key',
            'extra_key_en',
            'is_extra',
            'is_use_calculate_vat',
            'extra_sort_no',
            'branch_id'
        ];

        // protected $hidden = [];
        // protected $dates = [
    
        // ];

        // protected $casts = [
    
        // ];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */ 
        public function canDelete()
        {
            return $this->rooms->count() == 0 && !$this->is_use_calculate_vat ;
        }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
        public function rooms()
        {
            return $this->belongsToMany(
                'App\Models\RoomStayHistory', 
                'room_utility_histories', 
                'setting_id', 
                'room_stay_history_id'
            );
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
