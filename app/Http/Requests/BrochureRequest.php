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
        return [
            'new_referred' => 'required|boolean',
            'quotation_id' => 'nullable|exists:quotations,id',

            'city_id' => 'nullable|exists:cities,id',
            'identification_type' => ['nullable', Rule::in([Customer::CEDULA, Customer::RUC, Customer::PASAPORTE])],
            'identification'=>'nullable|max:20|unique:customers,identification,NULL,id,deleted_at,NULL',
            'names' => 'nullable|max:50',
            'surnames' => 'nullable|max:50',
            'cell_number' => 'nullable|digits:10',
            'email' => 'nullable|max:150|email',

            'plan_id' => 'nullable|exists:plans,id',
            'preference_id' => 'nullable|exists:preferences,id',
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
            'new_referred' => 'enviar a',
            'quotation_id' => 'referido',

            'city_id' => 'ciudad',
            'identification_type' => 'tipo de documento',
            'identification' => 'número de documento',
            'names' => 'nombres',
            'surnames' => 'apellidos',
            'cell_number' => 'teléfono móvil',
            'email' => 'correo electrónico',

            'plan_id' => 'plan',
            'preference_id' => 'preferencia',
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
