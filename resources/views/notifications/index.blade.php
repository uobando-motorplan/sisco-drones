@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><a href="javascript: void(0);">Notificaciones</a></li>
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
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="datatable">
                            <thead>
                                <tr>
                                    <th>Titulo</th>
                                    <th>Descripción</th>
                                    <th>Creada en</th>
                                    <th>Leída en</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- start modal -->
    <div class="modal fade" id="destroyModal" tabindex="-1" role="dialog" aria-labelledby="destroyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="card-title font-16 my-0" id="destroyModalLabel">Eliminar notificación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de eliminar la notificación <strong></strong>?
                </div>
                <div class="modal-footer">
                    <form action="" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger px-4 mr-1">Sí</button>
                        <button type="button" class="btn btn-sm btn-secondary px-4" data-dismiss="modal">No</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->
@endsection

@push('js')
    <script type="text/javascript">
    var dtable = $('#datatable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        processing: true,
        serverSide: true,
        ajax: '{{ route('notifications.datatables') }}',
        language: {
            url: "{{ asset('assets/libs/datatables.net/Spanish.json') }}"
        },
        columns: [
            {data: 'title'},
            {data: 'text'},
            {data: 'read_at'},
            {data: 'created_at'},
            {data: 'actions', orderable: false, searchable: false}
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
        order: [[3, 'desc']]
    });
    $('#destroyModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var name = button.data('name')
        var url = button.data('url')
        var modal = $(this)
        modal.find('.modal-body strong').text(name)
        modal.find('.modal-footer form').attr('action', url)
    })
    </script>
@endpush
