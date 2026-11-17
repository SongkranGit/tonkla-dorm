<?php


namespace App\Helpers;
use Illuminate\Support\Facades\Config;

class BranchHelper
{
    public static function getCurrentBranchId()
    {
        return session('current_branch_id');
    }

    public static function isBranchHotel(){
        $branch_hotel = Config::get('constants.branches.hotel');
        $branch_id = session('current_branch_id');
        if($branch_hotel == $branch_id){
            return true;
        }else{
            return false;
        }
    }
}