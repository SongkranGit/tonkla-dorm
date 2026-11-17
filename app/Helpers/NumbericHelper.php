<?php 

namespace App\Helpers;

class NumbericHelper
{
    public static function getNumber($value) 
    {
        $result = 0;
        if($value != null) {
            $result = str_replace(',', '', $value);
            $result = str_replace('%', '', $result);
            $result = str_replace(' ', '', $result);
            $result = floatval(preg_replace('/[^\d.]/', '', $result));
        }
        return $result;
    }
}