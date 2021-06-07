@if ($quotation->status_id == App\Status::CERRADO)
	@if ($quotation->closure_reason_id == App\ClosureReason::CERRADO_CON_VENTA)
		<span class="text-success">Venta cerrada</span>
	@else
		<span class="text-danger">Finalizado sin venta</span>
	@endif
@else
	<span class="text-info">En seguimiento</span>
@endif