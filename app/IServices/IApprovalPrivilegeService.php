<?php


namespace App\IServices;


interface IApprovalPrivilegeService
{
    public function getAllApprovalPrivileges();

    public function checkCanApproveByUserId($userId);
}