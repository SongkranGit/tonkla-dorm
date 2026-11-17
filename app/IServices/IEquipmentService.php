<?php

namespace App\IServices;

use Illuminate\Http\Request;

interface IEquipmentService
{
    
    public function getAllEquipmentWithPagination(Request $request); 

    public function create(Request $request);  

    public function updateEquipment(Request $request, $id);
    
    public function setActivityLog($actionType, $equipment, $description = "");
    
}