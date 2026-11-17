<?php

namespace App\IServices;

use Illuminate\Http\Request;

interface ISettingService
{

    public function all();

    public function getAllExtraWithPagination(Request $request); 
    
    public function getAllExtra(); 
    
    public function getByStorageId($id);
    
    /**
     * Return setting value or default value by key.
     *
     * @param  string  $key
     * @param  string  $value
     * @return string|null
     */
    public function get($key, $default_value = null);

    /**
     * Set the setting by key and value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function set($key, $value);

    public function delete($id);

    public function getSettingByKey($key);

}