<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeactivateAccountRequest extends FormRequest
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
            'drone_deactivation_reason_id' => 'required|exists:drone_deactivation_reasons,id',
            'comment' => 'nullable|max:255',
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
            'drone_deactivation_reason_id' => 'razón de cancelación',
            'comment' => 'comentario',
        ];
    }
}
