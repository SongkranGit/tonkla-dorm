<?php

namespace App\IServices;

use Illuminate\Http\Request;

interface IEquipmentHistoryService
{ 

    public function getAllWithPagination(Request $request); 

    public function updateEquipmentHistory(Request $request,$id); 

}