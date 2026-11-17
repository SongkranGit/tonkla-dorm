<?php

namespace App\IServices;

use Illuminate\Http\Request;

interface IRoomHistoryService
{ 

    public function getAllWithPagination(Request $request); 
   
    
    //public function updateRoomHistory(Request $request,$id); 

}