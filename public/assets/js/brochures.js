// Mostrar iframe de la galería de fotos y videos
$('#itemShowModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    var title = button.data('name');
    var url = button.data('url');
    var modal = $(this);
    modal.find('.modal-body iframe').attr('src', url)
    modal.find('.modal-header .modal-title').text(title)
});

// Ocultar el iframe de la galería de fotos y video
$('#itemShowModal').on('hidden.bs.modal', function(event) {
    var modal = $(this);
    modal.find('.modal-body iframe').attr('src', '')
});

// Imprimir página
$('a.printPage').click(function() {
    window.print();
    return false;
});