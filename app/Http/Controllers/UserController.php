<?php

namespace App\Http\Controllers;

use App\Bank;
use App\User;
use App\Drone;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DroneSuspensionReason;
use Illuminate\Support\Carbon;
use App\DroneDeactivationReason;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\MyEmailRequest;
use App\Http\Requests\MyPhotoRequest;
use Intervention\Image\Facades\Image;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\MyPasswordRequest;
use App\Http\Requests\MyBankAccountRequest;
use App\Http\Requests\DeactivateAccountRequest;
use App\Notifications\EmailChangeNotificaction;
use App\Http\Requests\MyContactInformationRequest;
use App\Notifications\PasswordChangeNotificaction;
use App\Notifications\AccountDeactivatedNotification;
use App\Notifications\BankAccountChangeNotificaction;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $banks = Bank::orderBy('name')->pluck('name', 'id');
        $drone_deactivation_reasons = DroneDeactivationReason::orderBy('order')->pluck('name', 'id');

        return view('users.profile', compact('banks', 'drone_deactivation_reasons'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\MyPasswordRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update_password(MyPasswordRequest $request)
    {
        $this->authorize('update', auth()->user());

        if (Hash::check($request->current_password, auth()->user()->password)) {
            // Actualizo la clave del susuario
            auth()->user()->password = bcrypt($request->password);
            auth()->user()->save();

            // Envío una notificación al usuario
            auth()->user()->notify(new PasswordChangeNotificaction());

            return redirect()->back()
                ->with('success', 'La contraseña fue cambiada correctamente.');
        } else {
            return redirect()->back()
                ->with('warning', 'La contraseña actual es incorrecta.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\MyPhotoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update_photo(MyPhotoRequest $request)
    {
        $this->authorize('update', auth()->user());

        // Elimino la imagen anterior
        Storage::delete('/public/users/'.auth()->user()->photo);
        // Subo la imagen
        $image = $request->file('image')->store('public/users');
        // Redimensiono la imagen
        Image::make(storage_path().'/app/'.$image)->fit(512, 512)->save();

        // Actualizo el registro
        auth()->user()->photo = substr($image, strripos($image, '/')+1, strlen($image));
        auth()->user()->save();

        return redirect()->back()
            ->with('success', 'La foto fue actualizada correctamente.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\MyBankAccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update_bank(MyBankAccountRequest $request)
    {
        $this->authorize('update', auth()->user());

        // Actualizo la cuenta bancaria del dron
        Drone::whereId(auth()->user()->drone->id)
            ->update([
                'bank_id' => $request->bank_id,
                'bank_account_type' => $request->bank_account_type,
                'bank_account_number' => $request->bank_account_number,
            ]);

        // Envío una notificación al usuario
        auth()->user()->notify(new BankAccountChangeNotificaction());

        return redirect()->back()
            ->with('success', 'La cuenta bancaria fue actualizada correctamente.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\MyContactInformationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update_contact_information(MyContactInformationRequest $request)
    {
        $this->authorize('update', auth()->user());

        Drone::whereId(auth()->user()->drone->id)
            ->update([
                'mobile_phone' => $request->mobile_phone,
                'landline_phone' => $request->landline_phone,
            ]);

        return redirect()->back()
            ->with('success', 'La información de contacto fue actualizada correctamente.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\MyEmailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update_email(MyEmailRequest $request)
    {
        $this->authorize('update', auth()->user());

        // Consulto el dron
        $drone = Drone::find(auth()->user()->drone->id);

        // Actualizo el dron
        $drone->email = $request->email;
        $drone->save();

        // Verifico si el registro ha sido actualizado
        if ($drone->wasChanged()) {
            // Envío una notificación al usuario
            auth()->user()->notify(new EmailChangeNotificaction($request->email));

            // Actualizo el registro de usuario
            User::whereId(auth()->user()->id)
                ->update([
                    'email' => $request->email,
                ]);

            return redirect()->back()
                ->with('success', 'La dirección de correo electrónico fue actualizada correctamente.');
        }

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     * Usado la primera vez que inicia sesión para remplazar la clave temporal
     *
     * @return \Illuminate\Http\Response
     */
    public function replace_temporary_password()
    {
        $this->authorize('update', auth()->user());

        if (auth()->user()->created_at != auth()->user()->updated_at) {
            return redirect()->route('home');
        }

        return view('auth.passwords.replace_temporary_password');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_temporary_password(PasswordRequest $request)
    {
        $this->authorize('update', auth()->user());

        auth()->user()->password = bcrypt($request->password);
        auth()->user()->save();

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function documents()
    {
        return view('users.documents');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_contract_file(Request $request)
    {
        $this->authorize('update', auth()->user());

        // Valido el formulario
        $rules = ['contract_file' => 'required|mimes:pdf|max:4000'];
        $messages = [];
        $attributes = ['contract_file' => 'archivo'];
        $validated = $request->validate($rules, $messages, $attributes);

        $file_extension = $request->contract_file->extension();
        $original_file_name = $request->contract_file->getClientOriginalName();

        // Elimino el archivo anterior
        if (auth()->user()->drone->contract_file) {
            Storage::disk('s3')->delete('drones/contracts/'.auth()->user()->drone->contract_file);
        }
        // Subo el archivo nuevo
        $file_name = Str::random('40').'.'.strtolower($file_extension);
        $file_path = 'drones/contracts/'.$file_name;
        Storage::disk('s3')->put($file_path, file_get_contents($request->file('contract_file')));
        
        // Actualizo el registro
        auth()->user()->drone->contract_file = $file_name;
        auth()->user()->drone->save();

        // Inicio la revisión del marco legal y notifico al asistente de talento humano
        if (! auth()->user()->drone->legal_review_start_date 
            AND auth()->user()->drone->contract_file 
            AND auth()->user()->drone->confidentiality_agreement_file 
            AND auth()->user()->drone->ruc_file) {

            // Actualizo el estado
            auth()->user()->drone->legal_review_start_date = Carbon::now();
            auth()->user()->drone->save();

            // Envía una notificación al ASISTENTE DE TALENTO HUMANO para que evalue el MARCO LEGAL
            $url = env('SISCO_URL').'api/notifications/evaluate-legal-framework';
            $response = Http::withHeaders([
                'Authorization' => env('DRONES_KEY'),
            ])->get($url, [
                'drone_id' => auth()->user()->drone->id
            ]);
        }

        return redirect()->back()
            ->with('success', 'El contrato de comisión mercantil se subió correctamente.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_confidentiality_agreement_file(Request $request)
    {
        $this->authorize('update', auth()->user());

        // Valido el formulario
        $rules = ['confidentiality_agreement_file' => 'required|mimes:pdf|max:4000'];
        $messages = [];
        $attributes = ['confidentiality_agreement_file' => 'archivo'];
        $validated = $request->validate($rules, $messages, $attributes);

        $file_extension = $request->confidentiality_agreement_file->extension();
        $original_file_name = $request->confidentiality_agreement_file->getClientOriginalName();

        // Elimino el archivo anterior
        if (auth()->user()->drone->confidentiality_agreement_file) {
            Storage::disk('s3')->delete('drones/confidentiality-agreements/'.auth()->user()->drone->confidentiality_agreement_file);
        }
        // Subo el archivo nuevo
        $file_name = Str::random('40').'.'.strtolower($file_extension);
        $file_path = 'drones/confidentiality-agreements/'.$file_name;
        Storage::disk('s3')->put($file_path, file_get_contents($request->file('confidentiality_agreement_file')));

        // Actualizo el registro
        auth()->user()->drone->confidentiality_agreement_file = $file_name;
        auth()->user()->drone->save();

        // Inicio la revisión del marco legal y notifico al asistente de talento humano
        if (! auth()->user()->drone->legal_review_start_date 
            AND auth()->user()->drone->contract_file 
            AND auth()->user()->drone->confidentiality_agreement_file 
            AND auth()->user()->drone->ruc_file) {

            // Actualizo el estado
            auth()->user()->drone->legal_review_start_date = Carbon::now();
            auth()->user()->drone->save();

            // Envía una notificación al ASISTENTE DE TALENTO HUMANO para que evalue el MARCO LEGAL
            $url = env('SISCO_URL').'api/notifications/evaluate-legal-framework';
            $response = Http::withHeaders([
                'Authorization' => env('DRONES_KEY'),
            ])->get($url, [
                'drone_id' => auth()->user()->drone->id
            ]);
        }

        return redirect()->back()
            ->with('success', 'El acuerdo de confidencialidad se subió correctamente.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_ruc_file(Request $request)
    {
        $this->authorize('update', auth()->user());

        // Valido el formulario
        $rules = ['ruc_file' => 'required|mimes:pdf|max:4000'];
        $messages = [];
        $attributes = ['ruc_file' => 'archivo'];
        $validated = $request->validate($rules, $messages, $attributes);

        $file_extension = $request->ruc_file->extension();
        $original_file_name = $request->ruc_file->getClientOriginalName();

        // Elimino el archivo anterior
        if (auth()->user()->drone->ruc_file) {
            Storage::disk('s3')->delete('drones/ruc/'.auth()->user()->drone->ruc_file);
        }
        // Subo el archivo nuevo
        $file_name = Str::random('40').'.'.strtolower($file_extension);
        $file_path = 'drones/ruc/'.$file_name;
        Storage::disk('s3')->put($file_path, file_get_contents($request->file('ruc_file')));

        // Actualizo el registro
        auth()->user()->drone->ruc_file = $file_name;
        auth()->user()->drone->save();

        // Inicio la revisión del marco legal y notifico al asistente de talento humano
        if (! auth()->user()->drone->legal_review_start_date 
            AND auth()->user()->drone->contract_file 
            AND auth()->user()->drone->confidentiality_agreement_file 
            AND auth()->user()->drone->ruc_file) {

            // Actualizo el estado
            auth()->user()->drone->legal_review_start_date = Carbon::now();
            auth()->user()->drone->save();

            // Envía una notificación al ASISTENTE DE TALENTO HUMANO para que evalue el MARCO LEGAL
            $url = env('SISCO_URL').'api/notifications/evaluate-legal-framework';
            $response = Http::withHeaders([
                'Authorization' => env('DRONES_KEY'),
            ])->get($url, [
                'drone_id' => auth()->user()->drone->id
            ]);
        }

        return redirect()->back()
            ->with('success', 'El RUC se subió correctamente.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\DeactivateAccountRequest
     * @return \Illuminate\Http\Response
     */
    public function deactivate_account(DeactivateAccountRequest $request)
    {
        // Desactivo el acceso a Sisco Drones (Usuario en Sisco)
        auth()->user()->active = false;
        auth()->user()->save();

        // Consulto el registro de dron
        $drone = Drone::find(auth()->user()->drone->id);

        // Desactivo el acceso a Portal Drones
        $drone->drone_suspension_reason_id = DroneSuspensionReason::SOLICITUD_USUARIO;
        $drone->drone_deactivation_reason_id = $request->drone_deactivation_reason_id;
        $drone->active = false;
        $drone->save();

        // Consulto el motivo de la cancelación
        $drone_suspension_reason = DroneSuspensionReason::find(DroneSuspensionReason::SOLICITUD_USUARIO);
        $drone_deactivation_reason = DroneDeactivationReason::find($request->drone_deactivation_reason_id);

        $description = 'El dron ha desactivado su cuenta de usuario Sisco Drones.<br>';
        $description .= '<i class="text-muted">Motivo: </i>'.$drone_suspension_reason->name.' - '.$drone_deactivation_reason->name.'<br>';
        if ($request->comment) {
            $description .= '<i class="text-muted">Cometario: </i>'.$request->comment;
        }

        // Creo una observación automática del sistema
        auth()->user()->drone->observations()->create([
            'user_id' => User::SISTEMA,
            'description' => $description
        ]);

        // Envio una notificación al dron
        auth()->user()->notify(new AccountDeactivatedNotification());

        // Envía una notificación al ASISTENTE DE TALENTO HUMANO para indicarle que un dron se ha desactivado
        $url = env('SISCO_URL').'api/notifications/deactivate-account';
        $response = Http::withHeaders([
            'Authorization' => env('DRONES_KEY'),
        ])->get($url, [
            'drone_id' => auth()->user()->drone->id
        ]);

        // Cierro la sesión del usuario
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('reactivate_account.index')
            ->with('success', 'Tu cuenta de usuario Ejecutivos Drones ha sido desactivada exitósamente.');
    }
}
