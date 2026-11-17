<?php


namespace App\Services;


use App\IServices\IRoomApprovalService;
use App\Models\RoomApproval;

class RoomApprovalService implements IRoomApprovalService
{

    public function __construct()
    {
    }

    public function requestForApprove($roomId, $requestCheckingType)
    {
        $roomApproval = RoomApproval::where('room_id', $roomId)->first();
        if ($roomApproval == null) {
            $roomApprove = new RoomApproval;
            $roomApprove->room_id = $roomId;
            $roomApprove->request_checking_type = $requestCheckingType;
            if ($requestCheckingType === 'check_in')
                $roomApprove->is_request_approve_checkin = true;
            else
                $roomApprove->is_request_approve_checkout = true;

            return $roomApprove->save();
        } else {
            // update
            $roomApproval->request_checking_type = $requestCheckingType;
            if ($requestCheckingType === 'check_in')
                $roomApproval->is_request_approve_checkin = true;
            else
                $roomApproval->is_request_approve_checkout = true;

            return $roomApproval->save();
        }
        return 0;
    }



    public function getAllRequestForApprovals()
    {
        $roomApprovalList = RoomApproval::where('rooms.branch_id' , session('current_branch_id'))
            ->join('rooms', "rooms.id" , "=", 'room_approval.room_id')
            ->where('room_approval.is_request_approve_checkin', 1)
            ->orWhere('room_approval.is_request_approve_checkout', 1)->get();
        return $roomApprovalList;
    }
}