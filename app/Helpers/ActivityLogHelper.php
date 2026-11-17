<?php 

namespace App\Helpers;

class ActivityLogHelper
{
    public static function getRoomActivityLogName($item)
    {
        if($item->log_name == 'room_updated')
        {
            return __('shared.room_change_history_room_updated');
        } 
        else if($item->log_name == 'room_moved_to')
        {
            return __('shared.room_change_history_room_moved_to').' '.$item->description;
        }
        else if($item->log_name == 'room_moved_from')
        {
            return __('shared.room_change_history_room_moved_from').' '.$item->description;
        }
        else if($item->log_name == 'room_printed_checkout')
        {
            return  __('shared.room_change_history_room_printed_checkout');
        } 
        else if($item->log_name == 'room_printed_checkin')
        {
            return  __('shared.room_change_history_room_printed_checkin');
        } 
        else 
        {
            return  __('shared.room_change_history_room_printed');
        } 
    }
}