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

// Actualizo la lista de ciudades
$('#province_id').on('change', function() {
    $('#city_id option:not(:first)').remove();
    $('#city_id').prop('disabled', true);

    if ($(this).val()) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('content')
            }
        });
        var request = $.ajax({
            method: 'get',
            data: {
                province_id: $(this).val()
            },
            xhrFields: {
                withCredentials: true
            },
            dataType: 'json',
            url: $(this).attr('data-url')
        });
        request.done(function(data) {
            $.each(data, function(i) {
                $('#city_id').append($('<option>').text(data[i].name).attr('value', data[i].id));
            });
        });
        request.fail(function(jqXHR, textStatus) {
            console.log('Request failed: ' + textStatus);
        });
    }
    $('#city_id').prop('disabled', false);
});

// Actualizo la lista de planes y preferencias
$('#product_id').on('change', function() {
    $('#plan_id option:not(:first)').remove();
    $('#preference_id option:not(:first)').remove();
    $('#plan_id').prop('disabled', true);
    $('#preference_id').prop('disabled', true);

    if ($(this).val()) {
        if ($(this).val() == 1) { // CasaPlan
            $('#condition').parent().parent().addClass('d-none');
            quitar_validacion($('#condition'));
        } else { // MotorPlan
            $('#condition').parent().parent().removeClass('d-none');
            $('#has_reserved_the_property').parent().parent().addClass('d-none');
            agregar_validacion($('#condition'), 'required');
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('content')
            }
        });
        // Actualizo la lista de planes
        var request = $.ajax({
            method: 'get',
            data: {
                product_id: $(this).val()
            },
            xhrFields: {
                withCredentials: true
            },
            dataType: 'json',
            url: $(this).attr('data-plans-url')
        });
        request.done(function(data) {
            $.each(data, function(i) {
                $('#plan_id').append($('<option>').text('$' + data[i].amount).attr('value', data[i].id));
            });
        });
        request.fail(function(jqXHR, textStatus) {
            console.log('Request failed: ' + textStatus);
        });
        // Actualizo la lista de preferencias
        var request = $.ajax({
            method: 'get',
            data: {
                product_id: $(this).val()
            },
            xhrFields: {
                withCredentials: true
            },
            dataType: 'json',
            url: $(this).attr('data-preferences-url')
        });
        request.done(function(data) {
            $.each(data, function(i) {
                $('#preference_id').append($('<option>').text(data[i].name).attr('value', data[i].id));
            });
        });
        request.fail(function(jqXHR, textStatus) {
            console.log('Request failed: ' + textStatus);
        });
    } else {
        $('#condition').parent().parent().addClass('d-none');
        $('#has_reserved_the_property').parent().parent().addClass('d-none');
    }
    $('#plan_id').prop('disabled', false);
    $('#preference_id').prop('disabled', false);
});

// Muestro el campo de propiedad reservada
$('#preference_id').on('change', function() {
    if ($(this).val()) {
        if ($('#product_id').val() == 1) {
            if ($(this).val() != 1 && $(this).val() != 3 && $(this).val() != 6) {
                $('#has_reserved_the_property').parent().parent().removeClass('d-none');
            } else {
                $('#has_reserved_the_property').parent().parent().addClass('d-none');
            }
        } else {
            $('#has_reserved_the_property').parent().parent().addClass('d-none');
        }
    } else {
        $('#has_reserved_the_property').parent().parent().addClass('d-none');
    }
});

// Muestro el campo de propiedad reservada
$('#has_applied_to_credit').on('change', function() {
    if ($(this).val() == 'S') {
        $('#why_didnt_buy').parent().parent().removeClass('d-none');
        agregar_validacion($('#why_didnt_buy'), 'required');
    } else {
        $('#why_didnt_buy').parent().parent().addClass('d-none');
        quitar_validacion($('#why_didnt_buy'));
    }
});