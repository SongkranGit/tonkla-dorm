<?php

namespace App\Http\Requests\Admin; 

class ExtraSettingUpdateRequest extends ExtraSettingBaseRequest
{ 
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value' => 'required|numeric',
            'key' => 'required',
            'extra_key' => 'required',
        ];
    }
}
