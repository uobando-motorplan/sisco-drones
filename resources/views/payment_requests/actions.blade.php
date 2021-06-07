@can('view', $payment_request)
	<a href="{{ route('payment_requests.show', $payment_request->id) }}" class="btn btn-sm btn-outline-secondary" title="Consultar registro"><i class="ri-eye-line"></i></a>
@endcan
@can('cancel', $payment_request)
	<a href="javascript:void(0);" class="btn btn-sm btn-outline-danger" title="Anular solicitud" data-toggle="modal" data-target="#destroyModal" data-name="#{{ $payment_request->id }}" data-url="{{ route('payment_requests.cancel', $payment_request->id) }}"><i class="ri-forbid-2-line"></i></a>
@endcan