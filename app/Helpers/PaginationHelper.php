<?php

namespace App\Helpers;

use Request;

class PaginationHelper
{
    public static function getPaginationColumn($url,$column, $columnName,$currentPage,$defaultSortColumn = 'id',$defaultSortOrder = 'asc')
    { 
        $sortOrder = Request::get('SortOrder');
        $sortColumn = Request::get('SortColumn');
        $icon = ''; 

        if($sortColumn == null)
        {
            $sortColumn = $defaultSortColumn;
        } 
        
        if($sortOrder == null)
        {
            $sortOrder = $defaultSortOrder; 
        }
        else if($sortColumn != $column)
        {
            $sortOrder = $sortOrder;
        }    

        if($sortColumn == $column) 
        { 
             if($sortOrder == 'desc')
            {
                $sortOrder = 'asc';
            }
            else if($sortOrder == 'asc')
            {
                $sortOrder = 'desc';
            }   
             
            $icon =  $sortOrder == 'asc' ? " <i class='fa fa-caret-up' aria-hidden='true'></i></a> " : " <i class='fa fa-caret-down' aria-hidden='true'></i></a> ";
        } 

        $url = url($url.'/?page='.$currentPage.'&SortOrder='.$sortOrder.'&SortColumn='.$column);
 
        return "<a href=".url($url)."> ".$columnName.$icon ;
    }

    public static function getPaginationCustomParameters()
    {
        $sortOrder = Request::get('SortOrder');
        $sortColumn = Request::get('SortColumn');
        $url = '&SortOrder='.$sortOrder.'&SortColumn='.$sortColumn;
        
        return $url;
    }
}