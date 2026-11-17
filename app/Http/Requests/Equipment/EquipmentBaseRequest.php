<?php

namespace App\Http\Requests\Equipment;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentBaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Custom Attributes name;
     *
     * @return array
     */
    public function attributes()
    { 
        return [
            'equipment_no' => __('shared.equipment_no'),
            'name' => __('shared.equipment_name'),
            'description' =>  __('shared.equipment_description'),
            'total_quantity' => __('shared.equipment_quantity')
        ]; 
    }
}
