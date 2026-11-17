<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptNumberGenerator extends Model
{
    /*
         |--------------------------------------------------------------------------
         | GLOBAL VARIABLES
         |--------------------------------------------------------------------------
         */

    protected $table = 'receipt_number_generator';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'branch_id',
        'running_number',
    ];

}