$(document).ready(function() {
    $('#formContacto').on('submit', function(e) {
        e.preventDefault();
        
        // Limpiar mensajes de error anteriores
        $('.error-message').empty();
        $('#mensajeRespuesta').empty().removeClass('text-success text-danger');
        
        // Validar campos
        var isValid = true;
        
        // Validar nombre cliente
        if (!$('#nombreCliente').val().trim()) {
            $('#errorNombreCliente').text('* Este campo es requerido').addClass('text-danger');
            isValid = false;
        }
        
        // Validar nombre empresa
        // if (!$('#nombreEmpresa').val().trim()) {
        //     $('#errorNombreEmpresa').text('* Este campo es requerido').addClass('text-danger');
        //     isValid = false;
        // }
        
        // Validar celular
        var celular = $('#celular').val().trim();
        if (!celular) {
            $('#errorCelular').text('* Este campo es requerido').addClass('text-danger');
            isValid = false;
        } else if (!/^[\d\s\-\+\(\)]+$/.test(celular)) {
            $('#errorCelular').text('* Ingrese un número de celular válido').addClass('text-danger');
            isValid = false;
        }
        
        // Validar email
        // var email = $('#email').val().trim();
        // if (!email) {
        //     $('#errorEmail').text('* Este campo es requerido').addClass('text-danger');
        //     isValid = false;
        // } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        //     $('#errorEmail').text('* Ingrese un email válido').addClass('text-danger');
        //     isValid = false;
        // }
        
        // Validar mensaje
        if (!$('#mensaje').val().trim()) {
            $('#errorMensaje').text('* Este campo es requerido').addClass('text-danger');
            isValid = false;
        }
        
        // Validar reCAPTCHA
        // var recaptchaResponse = grecaptcha.getResponse();
        // if (!recaptchaResponse) {
        //     $('#errorCaptcha').text('* Por favor, complete el reCAPTCHA').addClass('text-danger');
        //     isValid = false;
        // }
        
        // if (!isValid) {
        //     return false;
        // }
        
        // Mostrar spinner y deshabilitar botón
        $('#textoBoton').addClass('d-none');
        $('#spinnerBoton').removeClass('d-none');
        $('.botonEnviarContacto').prop('disabled', true);
        
        // Obtener datos del formulario
        var formData = $(this).serialize();
        
        // Enviar formulario
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#mensajeRespuesta')
                        .html('<div class="alert alert-success">' + response.message + '</div>')
                        .addClass('text-success');
                    $('#formContacto')[0].reset();
                    // grecaptcha.reset();
                } else {
                    $('#mensajeRespuesta')
                        .html('<div class="alert alert-danger">' + response.message + '</div>')
                        .addClass('text-danger');
                    // grecaptcha.reset();
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = 'Error al enviar el formulario. Por favor, intente nuevamente.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                $('#mensajeRespuesta')
                    .html('<div class="alert alert-danger">' + errorMessage + '</div>')
                    .addClass('text-danger');
                // grecaptcha.reset();
            },
            complete: function() {
                // Restaurar botón
                $('#textoBoton').removeClass('d-none');
                $('#spinnerBoton').addClass('d-none');
                $('.botonEnviarContacto').prop('disabled', false);
                
                // Scroll al mensaje
                $('html, body').animate({
                    scrollTop: $('#mensajeRespuesta').offset().top - 100
                }, 500);
            }
        });
        
        return false;
    });
    
    // Limpiar errores al escribir
    $('#nombreCliente, #nombreEmpresa, #celular, #email, #mensaje').on('input', function() {
        var fieldId = $(this).attr('id');
        $('#error' + fieldId.charAt(0).toUpperCase() + fieldId.slice(1)).empty();
    });
});

