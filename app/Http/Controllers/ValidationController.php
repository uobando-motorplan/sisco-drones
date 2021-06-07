<?php

namespace App\Http\Controllers;

use App\CustomClasses\ValidarIdentificacion;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    /**
     * Valida la cédula de identidad del prospecto
     *
     * @param  \Illuminate\Http\Request;  $request
     * @return \Illuminate\Http\Response
     */
    public function validar_cedula(Request $request)
    {
        $validar = new ValidarIdentificacion();

        if ($validar->validarCedula($request->identification)) {
            return array('valid'=>true);
        } else {
            return array('valid'=>false, 'message'=>'La cédula es inválida.');
        }
    }

    /**
     * Valida el ruc del prospecto
     *
     * @param  \Illuminate\Http\Request;  $request
     * @return \Illuminate\Http\Response
     */
    public function validar_ruc(Request $request)
    {
        $validar = new ValidarIdentificacion();

        if ($validar->validarRucPersonaNatural($request->identification)) {
            return array('valid'=>true);
        } else {
            return array('valid'=>false, 'message'=>'El RUC es inválido.');
        }
    }
}
