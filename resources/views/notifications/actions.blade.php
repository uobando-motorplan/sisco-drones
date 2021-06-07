@if ($notification->data['link'])
	<a href="{{ $notification->data['link'] }}" class="btn btn-sm btn-outline-info" title="Visitar enlace"><i class="ri-links-line"></i></a>
@endif
<a href="javascript:void(0);" class="btn btn-sm btn-outline-danger" title="Eliminar registro" data-toggle="modal" data-target="#destroyModal" data-name="{{ $notification->data['title'] }}" data-url="{{ route('notifications.destroy', $notification->id) }}"><i class="ri-delete-bin-line"></i></a>