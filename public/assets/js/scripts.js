// Marca todas las notificaciones como leídas
$('#page-header-notifications-dropdown').on('click', function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var request = $.ajax({
        method: 'post',
        xhrFields: {
            withCredentials: true
        },
        url: $(this).attr('data-url')
    });
    request.done(function() {
        console.log('Notificaciones leidas');
    });
    request.fail(function(jqXHR, textStatus) {
        console.log('Request failed: ' + textStatus);
    });
});

$(function() {
    // Mascaras
    $('#landline_phone').inputmask("(02|03|04|05|06|07) 9999999");
    $('#mobile_phone').inputmask("0\\999999999");
});

// form validator
$.validate({
    modules: 'security, sanitize',
    lang: 'es',
    onSuccess: function($form) {
        $('.btn').prop('disabled', true);
        $('form').css('opacity', '.5');
    }
});

/* BROCHURES */


// Muestra los artículos agregados al carrito
$('#page-header-brochure-dropdown').on('click', function() {
    $('#brochure-items').html('');
    $.ajax({
        method: 'GET',
        url: $(this).attr('data-url'),
        dataType: 'json',
        async: false
    }).done(function(data) {
        data.forEach(element => {
            $('#brochure-items').append('<a href="javascript:void(0);" class="text-reset notification-item"><div class="media"><div class="avatar-xs mr-3"><span class="avatar-title bg-info rounded-circle font-size-16"><i class="mdi mdi-' + element.icon + '"></i></span></div><div class="media-body"><h6 class="mt-0 mb-1">' + element.name + '</h6><div class="font-size-12"><p class="mb-0">' + element.price + '</p></div></div></div></a>');
        });
    }).fail(function(jqXHR, textStatus) {
        console.log('Request failed: ' + textStatus);
    });
});

jQuery(function() {
    var values = [13000, 15000, 16000, 17000, 18000, 19000, 20000, 21000, 22000, 23000, 24000, 25000, 26000, 27000, 28000, 29000, 30000, 31000, 32000, 33000, 34000, 35000, 36000, 37000, 38000, 39000, 40000, 41000, 42000, 43000, 44000, 45000, 46000, 47000, 48000, 49000, 50000, 55000, 60000, 65000, 70000, 75000, 80000];
    var values_p = ['$13,000', '$15,000', '$16,000', '$17,000', '$18,000', '$19,000', '$20,000', '$21,000', '$22,000', '$23,000', '$24,000', '$25,000', '$26,000', '$27,000', '$28,000', '$29,000', '$30,000', '$31,000', '$32,000', '$33,000', '$34,000', '$35,000', '$36,000', '$37,000', '$38,000', '$39,000', '$40,000', '$41,000', '$42,000', '$43,000', '$44,000', '$45,000', '$46,000', '$47,000', '$48,000', '$49,000', '$50,000', '$55,000', '$60,000', '$65,000', '$70,000', '$75,000', '$80,000'];

    // Aumento el rango para CASAPLAN
    if ($('#product_id').val() == 1) {
        values.push(999999);
        values_p.push('Más de $80,000');
    }

    // Rango de precio
    if ($('.js-range-slider').length) {
        $('.js-range-slider').ionRangeSlider({
            skin: 'round',
            type: 'double',
            from: values.indexOf(parseInt($('#from').val())),
            to: values.indexOf(parseInt($('#to').val())),
            values: values,
            prettify: function(n) {
                var ind = values.indexOf(n);
                return values_p[ind];
            },
            onStart: function(data) {
                rango_cuota(values[data.from], values[data.to])
            },
            onChange: function(data) {
                rango_cuota(values[data.from], values[data.to])
            }
        });
    }

    // Actualizo el rango de cuotas
    function rango_cuota(from, to) {
        $.ajax({ // Obtengo el rango de cuota
            method: 'GET',
            url: $('#range').attr('data-url'),
            data: {
                from,
                to
            },
            dataType: 'json',
        }).done(function(json) {
            $('.price-range').html('Cuota mensual de ' + json.from + ' - ' + json.to);
        }).fail(function(jqXHR, textStatus) {
            console.log('Request failed: ' + textStatus);
        });
    }

    // Agregar o quitar artículos al brochure
    $('.product-like').on('click', function() {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $.ajax({
                method: 'GET',
                url: $(this).attr('data-remove-url'),
                data: { id: $(this).attr('data-id') },
                dataType: 'json',
            }).done(function(data) {
                var count = Object.keys(data).length;
                if (count) {
                    $('#page-header-brochure-dropdown').find('.badge-danger').html(count);
                    $('.send-brochure').removeClass('disabled');
                    $('.send-brochure').addClass('btn-outline-primary');
                    $('.send-brochure').removeClass('btn-outline-secondary');
                } else {
                    $('#page-header-brochure-dropdown').find('.badge-danger').remove();
                    $('.send-brochure').addClass('disabled');
                    $('.send-brochure').addClass('btn-outline-secondary');
                    $('.send-brochure').removeClass('btn-outline-primary');
                }
            }).fail(function(jqXHR, textStatus) {
                console.log('Request failed: ' + textStatus);
            });
        } else {
            $(this).addClass('active');
            $.ajax({
                method: 'GET',
                url: $(this).attr('data-add-url'),
                data: { id: $(this).attr('data-id') },
                dataType: 'json',
            }).done(function(data) {
                var count = Object.keys(data).length;
                if ($('#page-header-brochure-dropdown').find('.badge-danger').length) {
                    $('#page-header-brochure-dropdown').find('.badge-danger').html(count);
                } else {
                    $('#page-header-brochure-dropdown').append('<span class="badge badge-danger badge-pill noti-icon-badge">' + count + '</span>');
                }
            }).fail(function(jqXHR, textStatus) {
                console.log('Request failed: ' + textStatus);
            });
            $('.send-brochure').removeClass('disabled');
            $('.send-brochure').addClass('btn-outline-primary');
            $('.send-brochure').removeClass('btn-outline-secondary');
        }
    });
});

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

// Tooltip
$('[data-toggle="tooltip"]').tooltip({
    trigger: 'hover'
});

// Muestra los artículos agregados al carrito
$('#page-header-brochure-dropdown').on('click', function() {
    $('#brochure-items').html('');
    $.ajax({
        method: 'GET',
        url: $(this).attr('data-url'),
        dataType: 'json',
        async: false
    }).done(function(data) {
        data.forEach(element => {
            $('#brochure-items').append('<a href="javascript:void(0);" class="text-reset notification-item"><div class="media"><div class="avatar-xs mr-3"><span class="avatar-title bg-info rounded-circle font-size-16"><i class="mdi mdi-' + element.icon + '"></i></span></div><div class="media-body"><h6 class="mt-0 mb-1">' + element.name + '</h6><div class="font-size-12"><p class="mb-0">' + element.price + '</p></div></div></div></a>');
        });
    }).fail(function(jqXHR, textStatus) {
        console.log('Request failed: ' + textStatus);
    });
});

$('#new_referred').on('change', function() {
    if ($(this).val()) {
        if ($(this).val() == 1) {
            $('#quotation_id').parent().parent().addClass('d-none');
            quitar_validacion($('#quotation_id'));

            $('#names').parent().parent().removeClass('d-none');
            $('#surnames').parent().parent().removeClass('d-none');
            $('#identification_type').parent().parent().removeClass('d-none');
            $('#identification').parent().parent().removeClass('d-none');
            $('#city_id').parent().parent().removeClass('d-none');
            $('#cell_number').parent().parent().removeClass('d-none');
            $('#email').parent().parent().removeClass('d-none');
            $('#plan_id').parent().parent().removeClass('d-none');
            $('#preference_id').parent().parent().removeClass('d-none');

            agregar_validacion($('#names'), 'required');
            agregar_validacion($('#surnames'), 'required');
            agregar_validacion($('#identification_type'), 'required');
            agregar_validacion($('#identification'), 'required');
            agregar_validacion($('#city_id'), 'required');
            agregar_validacion($('#cell_number'), 'required');
            agregar_validacion($('#email'), 'required email');
            agregar_validacion($('#plan_id'), 'required');
            agregar_validacion($('#preference_id'), 'required');
        } else {
            $('#quotation_id').parent().parent().removeClass('d-none');
            agregar_validacion($('#quotation_id'), 'required');

            quitar_campos();
        }
    } else {
        $('#quotation_id').parent().parent().addClass('d-none');
        quitar_validacion($('#quotation_id'));
        quitar_campos();
    }
});

// Agrego validación para cédula o ruc
$('#identification_type').on('change', function() {
    if ($(this).val()) {
        if ($(this).val() == 'C') {
            agregar_validacion($('#identification'), 'required number server');
            $('#identification').attr('data-validation-url', $(this).find(':selected').attr('data-url'));
            $('#identification').attr('maxlength', 10);
            $('#identification').attr('placeholder', '10 dígitos');
        }
        if ($(this).val() == 'R') {
            agregar_validacion($('#identification'), 'required number server');
            $('#identification').attr('data-validation-url', $(this).find(':selected').attr('data-url'));
            $('#identification').attr('maxlength', 13);
            $('#identification').attr('placeholder', '13 dígitos');
        }
        if ($(this).val() == 'P') {
            agregar_validacion($('#identification'), 'required number');
            $('#identification').attr('data-validation-url', '');
            $('#identification').attr('maxlength', 20);
            $('#identification').attr('placeholder', 'Máximo 20 caractres');
        }
    } else {
        quitar_validacion($('#identification'));
        $('#identification').attr('data-validation-url', '');
        $('#identification').attr('maxlength', 20);
        $('#identification').attr('placeholder', 'Máximo 20 caractres');
    }
});

function quitar_campos() {
    $('#names').parent().parent().addClass('d-none');
    $('#surnames').parent().parent().addClass('d-none');
    $('#identification_type').parent().parent().addClass('d-none');
    $('#identification').parent().parent().addClass('d-none');
    $('#city_id').parent().parent().addClass('d-none');
    $('#cell_number').parent().parent().addClass('d-none');
    $('#email').parent().parent().addClass('d-none');
    $('#plan_id').parent().parent().addClass('d-none');
    $('#preference_id').parent().parent().addClass('d-none');

    quitar_validacion($('#names'));
    quitar_validacion($('#surnames'));
    quitar_validacion($('#identification_type'));
    quitar_validacion($('#identification'));
    quitar_validacion($('#city_id'));
    quitar_validacion($('#cell_number'));
    quitar_validacion($('#email'));
    quitar_validacion($('#plan_id'));
    quitar_validacion($('#preference_id'));
}

jQuery(function() {
    // Hay errores de validación en el formulario de creación de brochure
    if ($('#form-errors').val() > 0) {
        $('#createModal').modal('show');
        $('#new_referred').trigger('change');
    }
});

// Agrego validacion a un campo de formulario
function agregar_validacion(elemento, validacion) {
    elemento.attr('data-validation', validacion);
}
// Quito validacion a un campo de formulario
function quitar_validacion(elemento) {
    elemento.removeClass('error');
    elemento.val('');
    elemento.attr('data-validation', '');
    elemento.attr('current-error', '');
    elemento.attr('style', '');
    elemento.closest('div').find('.form-error').remove();
    elemento.val(null).trigger("change");
}