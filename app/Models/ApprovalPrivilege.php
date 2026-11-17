<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ApprovalPrivilege extends Model
{

    protected $table = 'approval_privileges';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'can_approve',
        'created_at',
        'updated_at'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user(){
        return $this->hasOne('App\User', 'id','user_id');
    }

}
