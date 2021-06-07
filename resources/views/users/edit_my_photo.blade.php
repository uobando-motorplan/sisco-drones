@extends('layouts.app')

@section('title', 'Cambiar foto')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Cambiar foto</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-6">
            @if (session()->has('success'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="mdi mdi-check pr-2"></i> {{ session('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('users.update_my_photo') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            @if (auth()->user()->photo == 'default.png')
                                <img class="img-fluid" src="{{ asset('assets/images/'.auth()->user()->photo) }}" alt="avatar">
                            @else
                                <img class="img-fluid" src="/storage/users/{{ auth()->user()->photo }}" alt="avatar">
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="image"><small class="text-muted">(Tipo: jpg, png - 512 x 512 pixeles - Tamaño: 4Mb)</small></label>
                            <input type="file" class="form-control-file" name="image" id="image">
                            {!! $errors->first('image', '<span class="form-text form-error">:message</span>') !!}
                        </div>
                        <div class="mt-3">
	                        <button class="btn btn-secondary waves-effect waves-light mr-1" type="submit">Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

