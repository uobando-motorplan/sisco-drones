@extends('layouts.app')

@section('title', $folder->name)

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('folders.index') }}">Librería</a></li>
                        <li class="breadcrumb-item active"><a href="javascript: void(0);"></a>@yield('title')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->

    <div class="d-flex flex-wrap">
        @foreach ($folder->files->sortBy('name') as $file)
            <div class="resource text-center mb-3 mr-3">
                <a href="{{ env('SISCO_URL') }}storage/biblioteca/{{ $file->name }}" target="_blank"><img src="{{ asset('assets/images/archivo.png') }}" class="mb-2"></a><br>
                {{ $file->name }}
            </div>
        @endforeach
    </div>
@endsection
