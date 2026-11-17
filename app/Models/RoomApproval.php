<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RoomApproval extends Model
{

    protected $table = 'room_approval';
    protected $primaryKey = 'id';

    protected $fillable = [
        'room_id',
        'request_checking_type',
        'is_checkin_approved',
        'is_checkout_approved',
        'is_request_approve_checkin',
        'is_request_approve_checkout',
    ];

    public function room(){
        return $this->hasOne('App\Models\Room', 'id','room_id');
    }

}