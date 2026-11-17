<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Equipment extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

        protected $table = 'equipments';
        protected $primaryKey = 'id';

        // public $timestamps = false;
        // protected $guarded = ['id'];

        protected $fillable = [
            'branch_id',
            'equipment_no',
            'name',
            'description',
            'total_quantity',
            'available_quantity',
        ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */ 
        public function canDelete()
        {
            return $this->room_equipment->count() == 0;
        }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

        public function room_equipment()
        {
            return $this->hasMany('App\Models\RoomEquipment');
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
        public function getCreatedAtAttribute($value)
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
