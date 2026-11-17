<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExtraSettingStoreRequest extends ExtraSettingBaseRequest
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
            'extra_key' => 'required',
        ];
    }
}
