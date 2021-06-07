<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Drone;
use App\Http\Requests\MyBankAccountRequest;
use App\Http\Requests\MyContactInformationRequest;
use App\Http\Requests\MyEmailRequest;
use App\Http\Requests\MyPasswordRequest;
use App\Http\Requests\MyPhotoRequest;
use App\Http\Requests\PasswordRequest;
use App\Location;
use App\Notifications\BankAccountChangeNotificaction;
use App\Notifications\EmailChangeNotificaction;
use App\Notifications\PasswordChangeNotificaction;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

        return view('users.profile', compact('banks'));
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
}
