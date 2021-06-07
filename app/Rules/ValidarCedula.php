<?php

namespace App\Rules;

use App\CustomClasses\ValidarIdentificacion;
use Illuminate\Contracts\Validation\Rule;

class ValidarCedula implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $validar = new ValidarIdentificacion();

        return $validar->validarCedula($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La cédula es inválida.';
    }
}
