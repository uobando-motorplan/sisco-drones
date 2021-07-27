@extends('layouts.app')

@section('title', 'Mi documentación')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <p>La siguiente documentación es requerida para el pago de tus comisiones. Por favor complétala antes de realizar una solicitud de pago.</p>

            @if (session()->has('success'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="mdi mdi-check pr-2"></i> {{ session('success') }}
                </div>
            @endif
            <hr class="my-4">
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 mb-2">
            <h5>Contrato de comisión mercantil {!! auth()->user()->drone->contract_file ? '<small class="text-success">Subido</small>' : '<small class="text-muted">Pendiente</small>' !!}</h5>
            Descarga, firma, escanea y sube tu contrato de comisión mercantil. <br>
            <a href="{{ route('pdf.contract') }}" target="_blank" class="btn btn-outline-primary my-2 waves-effect waves-light">Descargar</a>
        </div>
        <div class="col-xl-6">
            <div class="card mb-0">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('users.update_contract_file') }}">
                        @csrf
                        @method('PUT')
                        <div class="d-flex justify-content-between">
                            <div class="mr-3">
                                <div class="form-group">
                                    <label for="contract_file">Archivo *</label> <small class="text-muted">(Tipo: PDF - Tamaño máximo: 4Mb)</small>
                                    <input type="file" name="contract_file" id="contract_file" class="form-control-file" data-validation="required mime size" data-validation-allowing="pdf" data-validation-max-size="4M">
                                    {!! $errors->first('contract_file', '<span class="form-text form-error">:message</span>') !!}
                                </div>
                                <button class="btn btn-secondary waves-effect waves-light" type="submit">Subir</button>
                            </div>
                            @if (auth()->user()->drone->contract_file)
                                <div class="align-self-center">
                                    <a href="{{ \Storage::disk('s3')->url('drones/contracts/'.auth()->user()->drone->contract_file) }}" target="_blank" class="text-info">
                                        <div class="text-center">
                                            <img class="" src="{{ asset('assets/images/pdf.png') }}" alt=""> <br>
                                            <small>ver archivo</small>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <hr class="my-4">
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 mb-2">
            <h5>Acuerdo de cofidencialidad y no divulgación de contenido {!! auth()->user()->drone->confidentiality_agreement_file ? '<small class="text-success">Subido</small>' : '<small class="text-muted">Pendiente</small>' !!}</small></h5>
            Descarga, firma, escanea y sube tu acuerdo de cofidencialidad. <br>
            <a href="{{ route('pdf.confidentiality_agreement') }}" target="_blank" class="btn btn-outline-primary my-2 waves-effect waves-light">Descargar</a>
        </div>
        <div class="col-xl-6">
            <div class="card mb-0">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('users.update_confidentiality_agreement_file') }}">
                        @csrf
                        @method('PUT')
                        <div class="d-flex justify-content-between align-self-center">
                            <div class="mr-3">
                                <div class="form-group">
                                    <label for="confidentiality_agreement_file">Archivo *</label> <small class="text-muted">(Tipo: PDF - Tamaño máximo: 4Mb)</small>
                                    <input type="file" name="confidentiality_agreement_file" id="confidentiality_agreement_file" class="form-control-file" data-validation="required mime size" data-validation-allowing="pdf" data-validation-max-size="4M">
                                    {!! $errors->first('confidentiality_agreement_file', '<span class="form-text form-error">:message</span>') !!}
                                </div>
                                <button class="btn btn-secondary waves-effect waves-light" type="submit">Subir</button>
                            </div>
                            @if (auth()->user()->drone->confidentiality_agreement_file)
                                <div class="align-self-center">
                                    <a href="{{ \Storage::disk('s3')->url('drones/confidentiality-agreements/'.auth()->user()->drone->confidentiality_agreement_file) }}" target="_blank" class="text-info">
                                        <div class="text-center">
                                            <img class="" src="{{ asset('assets/images/pdf.png') }}" alt=""> <br>
                                            <small>ver archivo</small>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <hr class="my-4">
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 mb-2">
            <h5>RUC (Registro Único de Contribuyentes)  {!! auth()->user()->drone->ruc_file ? '<small class="text-success">Subido</small>' : '<small class="text-muted">Pendiente</small>' !!}</small></h5>
            Escanea y sube tu RUC.
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('users.update_ruc_file') }}">
                        @csrf
                        @method('PUT')
                        <div class="d-flex justify-content-between align-self-center">
                            <div class="mr-3">
                                <div class="form-group">
                                    <label for="ruc_file">Archivo *</label> <small class="text-muted">(Tipo: PDF - Tamaño máximo: 4Mb)</small>
                                    <input type="file" name="ruc_file" id="ruc_file" class="form-control-file" data-validation="required mime size" data-validation-allowing="pdf" data-validation-max-size="4M">
                                    {!! $errors->first('ruc_file', '<span class="form-text form-error">:message</span>') !!}
                                </div>
                                <button class="btn btn-secondary waves-effect waves-light" type="submit">Subir</button>
                            </div>
                            @if (auth()->user()->drone->ruc_file)
                                <div class="align-self-center">
                                    <a href="{{ \Storage::disk('s3')->url('drones/ruc/'.auth()->user()->drone->ruc_file) }}" target="_blank" class="text-info">
                                        <div class="text-center">
                                            <img class="" src="{{ asset('assets/images/pdf.png') }}" alt=""> <br>
                                            <small>ver archivo</small>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection