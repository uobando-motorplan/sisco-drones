<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyPasswordRequest extends FormRequest
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
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
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
            'current_password' => 'contaseña actual',
            'password' => 'nueva contraseña',
        ];
    }
}
