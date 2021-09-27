<?php

namespace App\Http\Controllers\Auth;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\DroneSuspensionReason;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Send a reset link to the given user.
     *
     * Se sobreescribió el método para verificar si el usuario es un dron y está
     * activo antes de enviar el email de restableccimiento de contraseña.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $user = User::where('email', $request->email)
            ->where('role_id', Role::DRONE)
            ->first();

        if (!$user) {
            throw ValidationException::withMessages(['email' => 'Estas credenciales no coinciden con nuestros registros.']);
        }

        if (! $user->active) {
            if (in_array($user->drone->drone_suspension_reason_id, [DroneSuspensionReason::PROCESO_INCONCLUSO, DroneSuspensionReason::SOLICITUD_USUARIO])) {
                return redirect()->route('reactivate_account.index');
            } else {
                return back()->with('warning', 'Estas credenciales no coinciden con nuestros registros.');
            }
        } else {
            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );

            if ($response === Password::RESET_LINK_SENT) {
                return back()->with('status', trans($response));
            }

            return back()->withErrors(
                ['email' => trans($response)]
            );
        }
    }
}
