<?php

namespace App\IServices;

use Illuminate\Http\Request;

interface IRoomEquipmentService
{

    /**
     * @param array $roomEquipments
     *
     * @param int $roomId
     *
     * @return  bool
    **/     
    public function upsertRoomEquipments($roomEquipments,$equipmentId);  

    /**
     * @param array $roomEquipments
     *
     * @param int $roomId
     *
     * @return  bool
    **/     
    public function deleteRoomEquipments($roomEquipments);  

}