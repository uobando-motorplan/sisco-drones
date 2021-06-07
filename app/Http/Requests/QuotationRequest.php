<?php

namespace App\Http\Requests;

use App\Customer;
use App\Quotation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuotationRequest extends FormRequest
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
        if ($this->method() === 'POST') {
            return [
                'province_id' => 'required|exists:provinces,id',
                'city_id' => 'required|exists:cities,id',
                'contact_method_id' => 'required|exists:contact_methods,id',
                'occupation_id' => 'nullable|exists:occupations,id',
                'occupation_period_id' => 'nullable|exists:occupation_periods,id',
                'identification_type' => ['required', Rule::in([Customer::CEDULA, Customer::RUC, Customer::PASAPORTE])],
                'identification' => 'required|max:20|unique:customers,identification,NULL,id,deleted_at,NULL',
                'names' => 'required|max:50',
                'surnames' => 'required|max:50',
                'phone_number' => 'nullable|required_without:cell_number|digits:9',
                'cell_number' => 'nullable|required_without:phone_number|digits:10',
                'email' => 'required|max:150|email',
                'has_social_security' => ['nullable', Rule::in(['S', 'N'])],
                'can_pay_down_payment' => ['nullable', Rule::in(['S', 'N'])],
                'monthly_payment_capacity' => 'nullable|integer|min:1',
                'has_applied_to_credit' => ['nullable', Rule::in(['S', 'N'])],

                'plan_id' => 'required|exists:plans,id',
                'preference_id' => 'required|exists:preferences,id',
                'condition' => ['nullable', Rule::in([Quotation::NUEVO, Quotation::USADO, Quotation::SIN_PREFERENCIA])],
                'comment' => 'nullable|max:255',
                'drone_comment' => 'nullable|max:255',
                'product_use' => ['nullable', Rule::in([Quotation::PERSONAL, Quotation::TRABAJO])],
                'has_reserved_the_property' => ['nullable', Rule::in(['S', 'N'])],
                'why_didnt_buy' => 'nullable|max:100',
            ];
        } else {
            return [
                'province_id' => 'required|exists:provinces,id',
                'city_id' => 'required|exists:cities,id',
                'contact_method_id' => 'required|exists:contact_methods,id',
                'occupation_id' => 'nullable|exists:occupations,id',
                'occupation_period_id' => 'nullable|exists:occupation_periods,id',
                'identification_type' => ['required', Rule::in([Customer::CEDULA, Customer::RUC, Customer::PASAPORTE])],
                'identification' => 'required|max:20|unique:customers,identification,'.$this->quotation->customer->id.',id,deleted_at,NULL',
                'names' => 'required|max:50',
                'surnames' => 'required|max:50',
                'phone_number' => 'nullable|required_without:cell_number|digits:9',
                'cell_number' => 'nullable|required_without:phone_number|digits:10',
                'email' => 'required|max:150|email',
                'has_social_security' => ['nullable', Rule::in(['S', 'N'])],
                'can_pay_down_payment' => ['nullable', Rule::in(['S', 'N'])],
                'monthly_payment_capacity' => 'nullable|integer|min:1',
                'has_applied_to_credit' => ['nullable', Rule::in(['S', 'N'])],

                'plan_id' => 'required|exists:plans,id',
                'preference_id' => 'required|exists:preferences,id',
                'condition' => ['nullable', Rule::in([Quotation::NUEVO, Quotation::USADO, Quotation::SIN_PREFERENCIA])],
                'comment' => 'nullable|max:255',
                'drone_comment' => 'nullable|max:255',
                'product_use' => ['nullable', Rule::in([Quotation::PERSONAL, Quotation::TRABAJO])],
                'has_reserved_the_property' => ['nullable', Rule::in(['S', 'N'])],
                'why_didnt_buy' => 'nullable|max:100',
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
            'province_id' => 'provincia',
            'city_id' => 'ciudad',
            'contact_method_id' => 'método de contacto',
            'occupation_id' => 'ocupación',
            'occupation_period_id' => 'periodo',
            'identification_type' => 'tipo de documento',
            'identification' => 'número de documento',
            'names' => 'nombres',
            'surnames' => 'apellidos',
            'phone_number' => 'teléfono fijo',
            'cell_number' => 'teléfono móvil',
            'email' => 'correo electrónico',
            'has_social_security' => 'está afiliado al iees',
            'can_pay_down_payment' => 'tiene dinero para una entrada',
            'monthly_payment_capacity' => 'cuánto puede pagar mensualmente',
            'has_applied_to_credit' => 'aplicó a un tipo crédito',
            'why_didnt_buy' => 'por qué no compró por ahí',

            'plan_id' => 'plan',
            'preference_id' => 'preferencia',
            'condition' => 'condición',
            'comment' => 'comentario del referido',
            'drone_comment' => 'observaciones para el vendedor',
            'use' => 'para qué usará el bien',
            'reserved' => 'dejó reservado el bien',
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
            'phone_number.required_without' => 'El campo teléfono fijo es obligatorio cuando teléfono móvil no está presente.',
            'cell_number.required_without' => 'El campo teléfono móvil es obligatorio cuando teléfono fijo no está presente.',
        ];
    }
}
