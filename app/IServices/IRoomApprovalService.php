<?php


namespace App\IServices;


interface IRoomApprovalService
{
    public function requestForApprove($roomId, $requestCheckingType);

    public function getAllRequestForApprovals();
}