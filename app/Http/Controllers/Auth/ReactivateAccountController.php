<?php

namespace App\Http\Controllers\Auth;

use App\Role;
use App\User;
use App\Drone;
use Illuminate\Support\Str;
use App\ReactivationRequest;
use Illuminate\Http\Request;
use App\DroneSuspensionReason;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReactivateAccountNotification;

class ReactivateAccountController extends Controller
{
    /**
     * Show the form for reactivate user account.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::guest()) {
            return redirect()->route('home');
        }

        return view('auth.reactivate_account');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function email(Request $request)
    {
        // Valido el formulario
        $rules = ['email' => 'required|email'];
        $messages = [];
        $attributes = ['email' => 'correo electrónico'];
        $validated = $request->validate($rules, $messages, $attributes);

        // Consulto los datos del usuario
        $user = User::select('id', 'drone_id', 'name', 'email', 'active')
            ->whereEmail($request->email)
            ->whereRoleId(Role::DRONE)
            ->with('drone:id,drone_suspension_reason_id')
            ->first();

        // Valido que el usuario exista, que sea de un dron y que no haya sido suspendido desde sisco
        if (! $user OR ! in_array($user->drone->drone_suspension_reason_id, 
            [DroneSuspensionReason::PROCESO_INCONCLUSO, DroneSuspensionReason::SOLICITUD_USUARIO])) {
            return redirect()->back()->with('warning', 'Estas credenciales no coinciden con nuestros registros.');
        }

        // Valido que el usuario esté inactivo
        if ($user->active) {
            return redirect()->back()->with('warning', 'La cuenta de usuario se encuentra activa.');
        }

        // Elimino el token de reactivación anterior
        ReactivationRequest::whereEmail($user->email)->delete();

        // Creo un token de reactivación
        $reactivation_request = new ReactivationRequest;
        $reactivation_request->email = $user->email;
        $reactivation_request->token = Str::random(60);
        $reactivation_request->save();

        // Envío un mensaje de correo electrónico con el link para reactivar la cuenta
        $user->notify(new ReactivateAccountNotification($reactivation_request->token));

        return redirect()->back()->with('success', 'El mensaje de correo electrónico fue enviado.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($token)
    {
        // Consulto el token de reactivación
        $reactivation_request = ReactivationRequest::where('token', $token)->first();

        // Valido que el token exista
        if (!$reactivation_request) {
            return redirect()
                ->route('reactivate_account.index')
                ->with('warning', 'El enlace de reactivación de cuenta ha caducado.');
        }

        // Calculo la diferencia en horas entre la hora actual y la hora de creación del token
        $total_duration = Carbon::now()->diffInHours($reactivation_request->created_at);

        // Verifico si el token ha expirado
        if ($total_duration >= 1) {
            // Elimino el token expirado
            ReactivationRequest::whereToken($token)->delete();

            return redirect()
                ->route('reactivate_account.index')
                ->with('warning', 'El enlace de reactivación de cuenta ha caducado.');
        }

        // Elimino el token
        ReactivationRequest::whereToken($token)->delete();

        // Consulto el usuario
        $user = User::where('email', $reactivation_request->email)->firstOrFail();

        // Activo el acceso a Sisco Drones
        $user->active = true;
        $user->save();

        // Activo el acceso a Portal Drones
        Drone::where('id', $user->drone_id)->update([
            'drone_suspension_reason_id' => null,
            'drone_deactivation_reason_id' => null,
            'reactivated_date' => Carbon::now(),
            'active' => true
        ]);

        // Creo la observación del usuario
        $user->drone->observations()->create([
            'user_id' => User::SISTEMA,
            'description' => 'El dron ha reactivado su cuenta de usuario Sisco Drones.',
        ]);

        // Inicio sesión
        if (Auth::loginUsingId($user->id)) {
            return redirect()->route('home');
        }

        return redirect('login');
    }
}
