<?php

namespace App\IServices;

use Illuminate\Http\Request;

interface IKeycardService
{

    /**
     * @param array $keycards
     *
     * @param int $roomId
     *
     * @return  bool
    **/     
    public function upsertKeycards($keycards,$roomId);  

    /**
     * @param array $keycards
     *
     * @param int $roomId
     *
     * @return  bool
    **/     
    public function deleteKeycards($keycards);  

}