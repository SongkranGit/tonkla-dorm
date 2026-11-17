<?php

namespace App\Http\Requests\Equipment;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentStoreRequest extends EquipmentBaseRequest
{ 

    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'equipment_no' => 'required',
            'name' => 'required',
            'description' => 'required',
            'total_quantity' => 'required|numeric'
        ];
    }
}
