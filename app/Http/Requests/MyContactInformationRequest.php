<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyContactInformationRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mobile_phone' => 'nullable|size:10',
            'landline_phone' => 'nullable|size:9',
        ];
    }

    /**
     *
     * Defino como se va a mostrar el nombre de los campos
     *
     */
    public function attributes()
    {
        return [
            'mobile_phone' => 'teléfono móvil',
            'landline_phone' => 'teléfono fijo',
        ];
    }
}
