<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyEmailRequest extends FormRequest
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
            'email'=> 'required|email|unique:users,email,'.auth()->user()->id.',id,deleted_at,NULL|unique:drones,email,'.auth()->user()->id.',id,deleted_at,NULL'
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
            'email' => 'correo electrónico',
        ];
    }
}
