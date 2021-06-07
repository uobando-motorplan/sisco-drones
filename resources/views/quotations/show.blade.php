@extends('layouts.app')

@section('title', 'Consultar referido')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Referidos</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">Listar</a></li>
                        <li class="breadcrumb-item active">Consultar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row h-100 mb-3">
                        <div class="col-9 my-auto">
                            <h3 class="card-title mb-0">{{ $quotation->customer->getFullName() }}</h3>
                        </div>
                        <div class="col-3 my-auto">
                            @can('update', $quotation)
                                <a href="{{ route('quotations.edit', $quotation->id) }}" class="btn btn-sm btn-outline-info float-right" title="Editar registro"><i class="ri-pencil-line"></i></a>
                            @endcan
                        </div>
                    </div>
                    
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#quotation-tab" role="tab">
                                <span class="d-block d-sm-none">Oportunidad</span>
                                <span class="d-none d-sm-block">Oportunidad comercial</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#customer-tab" role="tab">
                                <span class="d-block d-sm-none">Referido</span>
                                <span class="d-none d-sm-block">Referido</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#seller-tab" role="tab">
                                <span class="d-block d-sm-none">Asesor</span>
                                <span class="d-none d-sm-block">Asesor comercial</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content p-3">
                        <div class="tab-pane active" id="quotation-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                    <i class="text-muted d-none d-sm-inline">Id:</i><i class="text-muted d-inline d-sm-none">Oportunidad comercial:</i> {{ $quotation->id }}<br>
                                    <i class="text-muted">Monto:</i> ${{ number_format($quotation->plan->amount) }}<br>
                                    @if ($quotation->discount > 0)
                                        <i class="text-muted">Descuento:</i> <strong><span class="text-danger">{{ $quotation->discount }}%</span></strong><br>
                                    @endif
                                    <i class="text-muted">Producto:</i> {{ $quotation->plan->product->name }}<br>
                                    <i class="text-muted">Preferencia:</i> {{ $quotation->preference_id ? $quotation->preference->name : 'No hay preferencia' }}<br>
                                    @if ($quotation->plan->product->id == App\Product::MOTORPLAN)
                                        <i class="text-muted">Condición:</i> {{ $quotation->condition == App\Quotation::NUEVO ? 'Nuevo' : 
                                            ($quotation->condition === App\Quotation::USADO ? 'Usado' : 'No especificado') }}<br>
                                    @endif
                                    <i class="text-muted">¿Para qué usará el bien?:</i> {{ $quotation->product_use == App\Quotation::PERSONAL ? 'Uso personal' : 
                                            ($quotation->product_use === App\Quotation::TRABAJO ? 'Para trabajo' : '') }}<br>
                                    @if ($quotation->plan->product->id == App\Product::CASAPLAN AND ! in_array($quotation->preference_id, [1, 3, 6]))
                                        <i class="text-muted">¿Dejó reservado el bien?:</i> {{ $quotation->has_reserved_the_property == 'S' ? 'Sí' : 
                                            ($quotation->has_reserved_the_property == 'N' ? 'No' : '') }}<br>
                                    @endif
                                </div>
                                <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                    <i class="text-muted">Creado en:</i> {{ $quotation->created_at }}<br>
                                    <i class="text-muted">Modificado en:</i> {{ $quotation->updated_at }}<br>
                                    @if ($quotation->attended_at)
                                        <i class="text-muted">Atendido en:</i> {{ $quotation->attended_at }}<br>
                                    @endif
                                    <i class="text-muted">{{ $quotation->attended_at ? 'Tiempo de espera' : 'Esperando' }}:</i> <span class="{{ $quotation->created_at->diffInHours($quotation->attended_at) > 72 ? 'text-danger' : ($quotation->attended_at ? '' : '') }}">{{ $quotation->created_at->diffForHumans($quotation->attended_at, true, false, 4) }}</span><br>
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    <i class="text-muted">Estado:</i> {{ $quotation->status->description }}<br>
                                    @if ($quotation->status_id == App\Status::CERRADO)
                                        <i class="text-muted">Motivo:</i> {{ $quotation->closure_reason_id ? $quotation->closure_reason->description : '' }}<br>
                                    @endif
                                    @if (isset($quotation->score_id))
                                        @php
                                            if ($quotation->status_id == App\Status::CERRADO) {
                                                if ($quotation->closure_reason_id == App\ClosureReason::CERRADO_CON_VENTA) {
                                                    $color = 'badge-success';
                                                } else {
                                                    $color = 'badge-default';
                                                }
                                            } else {
                                                $color = 'badge-'.$quotation->score_id;
                                            }
                                        @endphp
                                        <i class="text-muted">Calificación:</i> <span class="badge {{ $color }} text-white">{{ $quotation->score_id }}: {{ $quotation->score->description }}</span><br>
                                    @endif
                                    @if ($quotation->score_id==App\Score::VENTA_CERRADA OR $quotation->score_id==App\Score::VENTA_CAIDA)
                                        <i class="text-muted">N° de solicitud:</i> {{ $quotation->admission_application }}<br>
                                    @endif
                                </div>
                            </div>
                            @if ($quotation->comment OR $quotation->observation)
                                <div class="row mt-3">
                                    <div class="col-12">
                                        @if ($quotation->comment)
                                            <i class="text-muted">Comentario del cliente:</i> {{ $quotation->comment }}<br>
                                        @endif
                                        @if ($quotation->observation)
                                            <i class="text-muted">Comentario para el vendedor:</i> {{ $quotation->observation }}<br>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane" id="customer-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                    <i class="text-muted d-none d-sm-inline">Id:</i><i class="text-muted d-inline d-sm-none">Prospecto:</i> {{ $quotation->customer->id }}<br>
                                    <i class="text-muted">Apellidos:</i> {{ $quotation->customer->surnames }}<br>
                                    <i class="text-muted">Nombres:</i> {{ $quotation->customer->names }}<br>
                                    <i class="text-muted">{{ $quotation->customer->getIdentificationType() }}:</i> {{ $quotation->customer->identification }}<br>
                                    <i class="text-muted">Ciudad:</i> {{ $quotation->customer->city->name }}, {{ $quotation->customer->city->province->name }}<br>
                                    @if ($quotation->customer->phone_number)
                                        <i class="text-muted">Teléfono fijo:</i> {{ $quotation->customer->phone_number }}<br>
                                    @endif
                                    @if ($quotation->customer->cell_number)
                                        <i class="text-muted">Teléfono móvil:</i> {{ $quotation->customer->cell_number }}<br>
                                    @endif
                                    <i class="text-muted">Email:</i> <a href="mailto:{{ $quotation->customer->email }}" class="text-info">{{ $quotation->customer->email }}</a><br>
                                    @if ($quotation->customer->address)
                                        <i class="text-muted">Dirección:</i> {{ $quotation->customer->address }}<br>
                                    @endif
                                    @if ($quotation->customer->sector)
                                        <i class="text-muted">Sector:</i> {{ $quotation->customer->sector ? $quotation->customer->sector->name : '' }}<br>
                                    @endif
                                </div>
                                <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                    <i class="text-muted">Método de contacto:</i> {{ $quotation->customer->contact_method ? $quotation->customer->contact_method->name : '' }}<br>
                                    @if ($quotation->customer->contact_schedule_id)
                                        <i class="text-muted">Horario de contacto:</i> {{ $quotation->customer->contact_schedule->name }}<br>
                                    @endif
                                    <i class="text-muted">¿Cómo se enteró de nosotros?:</i> {{ $quotation->customer->media_id ? $quotation->customer->media->name : '' }}<br>

                                    <i class="text-muted">Fuente de información:</i> {{ $quotation->source->name }}<br>
                                    <i class="text-muted">Creado en:</i> {{ $quotation->customer->created_at }}<br>
                                    <i class="text-muted">Modificado en:</i> {{ $quotation->customer->updated_at }}<br>
                                    <i class="text-muted">Estado:</i> 
                                    @php
                                        switch ($quotation->customer->status) {
                                            case App\Customer::COMPLETO:
                                                if ($quotation->customer->seller_id) {
                                                    $status = $quotation->customer->locked ? '<span class="badge badge-danger">Bloqueado</span>' : '<span class="badge badge-success">Liberado</span>';
                                                } else {
                                                    $status = '<span class="badge badge-secondary">No asignado</span>';
                                                }
                                                break;
                                            case App\Customer::INCOMPLETO:
                                                $status = '<span class="badge badge-secondary">Incompleto</span>';
                                                break;
                                            default:
                                                $status = '<span class="badge badge-secondary">Sin cobertura</span>';
                                                break;
                                        }
                                    @endphp
                                    {!! $status !!}
                                    @if ($quotation->customer->extended_lock == 1)
                                        <span class="badge badge-info ml-1">Bloqueo extendido</span>
                                    @endif
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    @if ( $quotation->customer->company)
                                        <i class="text-muted">Empresa donde labora:</i> {{ $quotation->customer->company }}<br>
                                    @endif
                                    @if ( $quotation->customer->company_phone_number)
                                        <i class="text-muted">Teléfono de la empresa:</i> {{ $quotation->customer->company_phone_number }}
                                    @endif
                                    <i class="text-muted">Ocupación:</i> {{ $quotation->customer->occupation_id ? $quotation->customer->occupation->name : '' }}<br>
                                    <i class="text-muted">Periodo:</i> {{ $quotation->customer->occupation_period_id ? $quotation->customer->occupation_period->name : '' }}<br>
                                    <i class="text-muted">¿Está afiliado al IESS?:</i> {{ $quotation->customer->has_social_security == 'S' ? 'Sí' : 
                                        ($quotation->customer->has_social_security == 'N' ? 'No' : '') }}<br>
                                    <i class="text-muted">¿Tiene dinero para una entrada?:</i> {{ $quotation->customer->can_pay_down_payment == 'S' ? 'Sí' : 
                                        ($quotation->customer->can_pay_down_payment == 'N' ? 'No' : '') }}<br>
                                    <i class="text-muted">¿Cuánto puede destinar a una cuota?:</i> {{ $quotation->customer->monthly_payment_capacity ? '$'.number_format($quotation->customer->monthly_payment_capacity) : '' }}<br>
                                    <i class="text-muted">¿Aplicó a un tipo crédito?:</i> {{ $quotation->customer->has_applied_to_credit == 'S' ? 'Sí' : 
                                        ($quotation->customer->has_applied_to_credit == 'N' ? 'No' : '') }}<br>
                                    @if ($quotation->customer->has_applied_to_credit == 'S')
                                        <i class="text-muted">¿Por qué no compró por ahí?:</i> {{ $quotation->customer->why_didnt_buy }}
                                    @endif
                                    @if ($customer_observations->count() > 0)
                                        <div class="mt-3">
                                            <a href="javascript:void(0);" data-toggle="modal" data-target=".bd-example-modal-lg">Leer {{ $customer_observations->count() }} observaciones</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="seller-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                    <i class="text-muted d-none d-sm-inline">Nombre:</i><i class="text-muted d-inline d-sm-none">Asesor comercial:</i> {{ $quotation->seller->getFullName() }}<br>
                                    <i class="text-muted">Email:</i> <a href="mailto:{{ $quotation->seller->email }}" class="text-info">{{ $quotation->seller->email }}</a><br>
                                    <i class="text-muted">Teléfono móvil:</i> {{ $quotation->seller->cell_number }}
                                    <div class="my-rating"></div>
                                </div>
                                <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                    <i class="text-muted">Grupo:</i> {{ $quotation->group->name}}<br>
                                    <i class="text-muted">Supervisor:</i> {{ $quotation->supervisor->getFullName()}}<br>
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    @if ($quotation->customer->assignator)
                                        <i class="text-muted">Asignado en:</i> {{ Carbon\Carbon::parse($quotation->customer->assigned_at) }}<br>
                                        <i class="text-muted">Asignado por:</i> {{ $quotation->customer->assignator->getFullName() }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-3">Seguimientos</h3>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Autor</th>
                                    <th>Observación</th>
                                    <th>Estado</th>
                                    <th>Seguimiento</th>
                                    <th>Calificación</th>
                                    <th>Creado en</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quotation_observations as $quotation_observation)
                                <tr>
                                    <td>{{ $quotation_observation->user->getFullName() }}</td>
                                    <td>{!! $quotation_observation->observation !!}</td>
                                    <td>{{ $quotation_observation->status ? $quotation_observation->status->description : '' }}</td>
                                    <td>{{ $quotation_observation->tracing_date ? $quotation_observation->tracing_date->format('d/m/Y H:i') : '' }}</td>
                                    <td>{{ $quotation_observation->score_id }} {{ isset($quotation_observation->score_id) ? ' - '.$quotation_observation->score->description : '' }}</td>
                                    <td>{{ $quotation_observation->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($quotation_observations->count() > 10)
                        <div class="mt-3">
                            {{ $quotation_observations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($brochures)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">Catálogo personalizado</h3>
                        @if (session()->has('success-brochure'))
                            <div class="alert alert-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="mdi mdi-check pr-2"></i> {{ session('success-brochure') }}
                            </div>
                        @endif
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Autor</th>
                                        <th># Items</th>
                                        <th>Creado en</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($brochures as $brochure)
                                        <tr>
                                            <td>{{ $brochure->user->getFullName() }}</td>
                                            <td>{{ $brochure->details_count }}</td>
                                            <td>{{ $brochure->created_at }}</td>
                                            <td>
                                                <a href="{{ route('brochures.show', $brochure->slug) }}" class="btn btn-sm btn-outline-secondary mr-1" title="Consultar registro" target="_blank"><i class="ri-eye-line"></i></a><a href="javascript:void(0);" class="btn btn-sm btn-outline-info" title="Reenviar email" data-toggle="modal" data-target="#notifyModal" data-url="{{ route('brochures.notify', $brochure->id) }}"><i class="ri-mail-line"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal -->
    @if ($customer_observations->count() > 0)
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title mt-1" id="myLargeModalLabel">Observaciones</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Autor</th>
                                    <th>Descripción</th>
                                    <th>Creado en</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customer_observations as $observation)
                                <tr>
                                    <td>{{ $observation->user->getFullName() }}</td>
                                    <td>{!! $observation->observation !!}</td>
                                    <td>{{ $observation->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($brochures)
        <!-- Modal -->
        <div class="modal fade" id="notifyModal" tabindex="-1" role="dialog" aria-labelledby="notifyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="card-title font-16 mt-1 mb-0" id="notifyModalLabel">Reenviar catálogo personalizado</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Está seguro de reenviar el correo electrónico del catálogo personalizado a tu referido?
                    </div>
                    <div class="modal-footer">
                        <form action="" method="post">
                            @csrf
                            <button type="button" class="btn btn-light mr-1" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-secondary">Reenviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/star-rating-svg.css') }}">
@endpush
@push('js')
    <script src="{{ asset('assets/js/jquery.star-rating-svg.js') }}"></script>
    <script type="text/javascript">
        $(".my-rating").starRating({
            useFullStars: true,
            strokeColor: '#ccc',
            strokeWidth: 10,
            starSize: 25,
            ratedColors: ['#ffa700', '#ffa700', '#ffa700', '#ffa700', '#ffa700'],
            // ratedColor: 'orange',
            callback: function(currentRating, $el){
                // make a server call here
            }
        });
        $('#notifyModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var url = button.data('url')
            var modal = $(this)
            modal.find('.modal-footer form').attr('action', url)
        });
    </script>
@endpush
