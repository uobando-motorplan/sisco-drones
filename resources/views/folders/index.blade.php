@extends('layouts.app')

@section('title', 'Librería')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><a href="javascript: void(0);"></a>@yield('title')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->

    <div class="d-flex flex-wrap">
        @foreach ($folders as $folder)
            <div class="resource text-center mb-3 mr-3">
                <a href="{{ route('folders.show', $folder->id) }}"><img src="{{ asset('assets/images/carpeta.png') }}" class="mb-2"></a><br>
                {{ $folder->name }}
            </div>
        @endforeach
    </div>
@endsection
