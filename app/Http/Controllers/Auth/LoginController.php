<?php

namespace App\Http\Controllers\Auth;

use App\Role;
use App\User;
use App\DroneSuspensionReason;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     *
     * Sobreescribo este método para verificar si el usuario está activo.
     *
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if (in_array($user->drone->drone_suspension_reason_id, [DroneSuspensionReason::PROCESO_INCONCLUSO, DroneSuspensionReason::SOLICITUD_USUARIO])) {
                return redirect()->route('reactivate_account.index');
            } else {
                return redirect()->back()->with('warning', 'Estas credenciales no coinciden con nuestros registros.');
            }

            return redirect()->back()->with('warning', $message);
        }
    }
}
