<?php
/**
 * Created by IntelliJ IDEA.
 * User: Admin
 * Date: 2019-04-08
 * Time: 6:12 PM
 */

namespace App\Helpers;

use App\Models\ApprovalPrivilege;
use App\Models\RoomApproval;
use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    public static function shouldShowMenu()
    {
        $should_show = true;
        if (!Auth::user()->hasRole('user') && session('should_show_branch_list')) {
            $should_show = false;
        }

        return $should_show;
    }

    public static function shouldShowSelectBranches()
    {
        return false;
    }

    public static function shouldShowButtonPrint($roomId, $checkingType)
    {
        $roomApproval = RoomApproval::where('room_id', $roomId)->first();
        // check role privilege
        if (Auth::user()->hasRole('owner')) {
            return true;
        } else {
            if ($roomApproval != null) {
                $isApproved = $checkingType == 'check_in' ? $roomApproval->is_checkin_approved : $roomApproval->is_checkout_approved;
                return boolval($isApproved);
            } else {
                return false;
            }
        }
    }

    public static function shouldShowWaitingForApproveButton($roomId, $checkingType)
    {
        $roomApproval = RoomApproval::where('room_id', $roomId)->first();
        if ($roomApproval != null) {
            if ($checkingType == 'check_in') {
                $is_approved = boolval($roomApproval->is_checkin_approved);
                $is_request_for_approve =  boolval($roomApproval->is_request_approve_checkin);
            } else {
                $is_approved = boolval($roomApproval->is_checkout_approved);
                $is_request_for_approve =  boolval($roomApproval->is_request_approve_checkout);
            }
            return $is_request_for_approve && !$is_approved;
        }
        return false;
    }

    public static function shouldShowApprovePrivilegeMenu()
    {
        if (Auth::user()->hasRole('owner')) {
            return true;
        }
        return false;
    }

    public static function shouldShowApprovalMenu()
    {
        $user = Auth::user();
        $approvalPrivilege = ApprovalPrivilege::where('user_id', $user->id)->first();
        $can_approve = $approvalPrivilege != null ? boolval($approvalPrivilege->can_approve) : false;
        if (!Auth::user()->hasRole('owner') && $can_approve) {
            return true;
        }
        return false;
    }


}