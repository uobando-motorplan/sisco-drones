<?php

namespace App\Rules;

use App\Customer;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidarDigitosIdentificacion implements Rule
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
        $customer = Customer::select('identification')
            ->where(DB::raw('substr(identification, 1, 10)'), '=' , substr($value, 0, 10))
            ->first();

        return $customer ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El número de identificación ya existe.';
    }
}
