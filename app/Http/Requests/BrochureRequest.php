<?php

namespace App\Http\Requests;

use App\Customer;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BrochureRequest extends FormRequest
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
        if ($this->new_referred == 1) {
            return [
                'names' => 'required|max:50',
                'surnames' => 'required|max:50',
                'identification_type' => ['required', Rule::in([Customer::CEDULA, Customer::RUC, Customer::PASAPORTE])],
                'identification'=>'required|max:15|unique:customers,identification,NULL,id,deleted_at,NULL',
                'city_id' => 'required|exists:cities,id',
                'email' => 'required|max:150|email',
                'cell_number' => 'required|digits:10',
                'plan_id' => 'required|exists:plans,id',
                'preference_id' => 'required|exists:preferences,id',
            ];
        } else {
            return [
                'quotation_id' => 'required|exists:quotations,id',
            ];
        }
    }

    /**
     *
     * Defino como se va a mostrar el nombre de los campos
     *
     */
    public function attributes()
    {
        return [
            'names' => 'nombres',
            'surnames' => 'apellidos',
            'identification_type' => 'tipo de documento',
            'identification' => 'número de documento',
            'city_id' => 'ciudad',
            'email' => 'correo electrónico',
            'cell_number' => 'teléfono móvil',
            'plan_id' => 'plan',
            'preference_id' => 'preferencia',

            'quotation_id' => 'referido',
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
            'identification_type.in' => 'El campo tipo de documento debe ser igual a cédula de identidad, RUC o pasaporte.',
        ];
    }
}
