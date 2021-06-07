<?php

namespace App\Http\Requests;

use App\Bank;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MyBankAccountRequest extends FormRequest
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
            'bank_id' => 'required|exists:banks,id',
            'bank_account_type' => ['required', Rule::in([Bank::AHORROS, Bank::CORRIENTE])],
            'bank_account_number' => 'required|max:20',
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
            'bank_id' => 'banco',
            'bank_account_type' => 'tipo de cuenta',
            'bank_account_number' => 'número de cuenta',
        ];
    }

    /**
     *
     * Mnesja personalizado
     *
     */
    public function messages()
    {
        return [
            'bank_account_type.in' => 'El campo tipo de cuenta debe ser igual a ahorros o correiente.',
        ];
    }
}
