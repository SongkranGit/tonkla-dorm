<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExtraSettingBaseRequest extends FormRequest
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
            'extra_key' => __('shared.extra_rate_extra_key'),
            'value' =>  __('shared.extra_rate_value')
        ]; 
    }
}
