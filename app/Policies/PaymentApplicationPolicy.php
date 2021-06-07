<?php

namespace App\Policies;

use App\PaymentApplication;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\PaymentApplication  $payment_application
     * @return mixed
     */
    public function view(User $user, PaymentApplication $payment_application)
    {
        return $payment_application->drone_id == $user->id;
    }

    /**
     * Determine whether the user can cancel the model.
     *
     * @param  \App\User  $user
     * @param  \App\PaymentApplication  $payment_application
     * @return mixed
     */
    public function cancel(User $user, PaymentApplication $payment_application)
    {
        return $payment_application->drone_id == $user->id AND $payment_application->status == PaymentApplication::NO_PAGADA;
    }
}
