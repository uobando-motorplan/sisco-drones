<?php

namespace App\Http\Requests;

use App\Bank;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\CustomClasses\ValidarIdentificacion;

class PaymentRequestRequest extends FormRequest
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
            'beneficiary_identification' => [
                'required', 
                'max:13', 
                function ($attribute, $value, $fail) {
                    $validar = new ValidarIdentificacion();
                    if (strlen($this->beneficiary_identification) == 10) {
                        if (! $validar->validarCedula($value)) {
                            $fail('La número de cédula es inválido.');
                        }
                    } else {
                        if (! $validar->validarRucPersonaNatural($value)) {
                            $fail('La número de cédula es inválido.');
                        }
                    }
                },
            ],
            'beneficiary_name' => 'required|max:100',
            'beneficiary_email' => 'required|email|max:100',
            'invoice_number' => 'required|email|size:17',
            'invoice_value' => 'required|numeric',
            'invoice_autorization_number' => 'required|digits:10',
            'invoice_autorization_date' => 'required|date',
        ];
    }
}
