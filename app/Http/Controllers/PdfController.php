<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    /**
     *
     * Class Constructor 
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contract()
    {
        $pdf = PDF::loadView('pdf.contract')->setPaper('a4', 'portrait');

        return $pdf->stream('Contrato-de-comisión-mercantil'.date('Ymdhis').'.pdf');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function confidentiality_agreement()
    {
        $pdf = PDF::loadView('pdf.confidentiality_agreement')->setPaper('a4', 'portrait');

        return $pdf->stream('Acuerdo-de-cofidencialidad-y-no-divulgación-de-contenido'.date('Ymdhis').'.pdf');
    }
}
