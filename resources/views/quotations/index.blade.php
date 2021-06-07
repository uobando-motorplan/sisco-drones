@extends('layouts.app')

@section('title', 'Referidos')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">@yield('title')</a></li>
                        <li class="breadcrumb-item active">Listar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            @if (session()->has('success'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="mdi mdi-check pr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if (session()->has('warning'))
                <div class="alert alert-warning" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="mdi mdi-alert-outline pr-2"></i> {{ session('warning') }}
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="datatable">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Producto</th>
                                    <th>Plan</th>
                                    <th>Asesor comercial</th>
                                    <th>Estado</th>
                                    <th>Actualizado en</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
    var dtable = $('#datatable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        processing: true,
        serverSide: true,
        ajax: '{{ route('quotations.datatables') }}',
        language: {
            url: "{{ asset('assets/libs/datatables.net/Spanish.json') }}"
        },
        columns: [
            {data: 'customer'},
            {data: 'plan.product.name'},
            {data: 'amount'},
            {data: 'seller'},
            {data: 'status'},
            {data: 'updated_at'},
            {data: 'actions', orderable: false, searchable: false, width: 66}
        ],
        initComplete: function() {
            $(".dataTables_filter input")
            .unbind()
            .bind("input", function(e) {
                if (this.value.length > 2 || e.keyCode == 13) {
                    dtable.search(this.value).draw();
                }
                if (this.value == "") {
                    dtable.search("").draw();
                }
                return;
            });
        },
        order: [[5, 'desc']]
    });
    </script>
@endpush
