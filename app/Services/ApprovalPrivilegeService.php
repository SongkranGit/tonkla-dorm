<?php


namespace App\Services;


use App\IServices\IApprovalPrivilegeService;
use App\Models\ApprovalPrivilege;

class ApprovalPrivilegeService implements IApprovalPrivilegeService
{

    public function getAllApprovalPrivileges()
    {
        $approvalPrivileges = ApprovalPrivilege:: get();
        return $approvalPrivileges;
    }

    public function checkCanApproveByUserId($userId)
    {
        $privilege = ApprovalPrivilege::where('user_id', $userId)->first();
        if (boolval($privilege->can_approve)) {
            return true;
        }
        return false;
    }
}