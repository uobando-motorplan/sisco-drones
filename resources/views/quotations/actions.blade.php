<a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-sm btn-outline-secondary" title="Consultar registro"><i class="ri-eye-line"></i></a>
@can('update', $quotation)
	<a href="{{ route('quotations.edit', $quotation->id) }}" class="btn btn-sm btn-outline-info" title="Editar registro"><i class="ri-pencil-line"></i></a>
@endcan