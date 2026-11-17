<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Keycard extends Model
{
        /*
        |--------------------------------------------------------------------------
        | GLOBAL VARIABLES
        |--------------------------------------------------------------------------
        */
            
        protected $table = 'keycards';
        protected $primaryKey = 'id';

        // public $timestamps = false;
        // protected $guarded = ['id'];
        
        protected $fillable = [
            'sort_no',  
            'key_card_no',  
            'taken_date',  
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

        public function room()
        {
            return $this->belongsTo('App\Models\Room');
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
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
