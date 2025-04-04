$(document).ready(function () {
    $('#registro').submit(function (e) {
        e.preventDefault();

        var form = this;
        var data = $(form).serialize();

        // Añadir botón submit pulsado
        var submitBtn = $('input[name="enviar"]');
        if (submitBtn.length) {
            data += '&' + encodeURIComponent(submitBtn.attr('name')) +
                    '=' + encodeURIComponent(submitBtn.val());
        }

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                $(form).removeClass('was-validated');

                // Limpiar feedback anterior
                $.each(form.elements, function () {
                    var input = this;
                    var $input = $(input);
                    var feedback = $input.closest('.input-group').find('.invalid-feedback');
                    $input.removeClass('is-invalid is-valid');
                    if (feedback.length) {
                        feedback.text('').hide();
                    }
                });

                // Mostrar errores si los hay
                $.each(response.errors, function (fieldName, message) {
                    var input = form.elements[fieldName];
                    if (input) {
                        var $input = $(input);
                        var feedback = $input.closest('.input-group').find('.invalid-feedback');
                        $input.addClass('is-invalid');
                        if (feedback.length) {
                            feedback.text(message).show();
                        }
                    }
                });

                if (response.success) {
                    // Reenviar formulario o redirigir
                    form.submit();
                    // O bien: window.location.href = 'success.php';
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error de red o servidor:', textStatus, errorThrown);
            }
        });
    });
});
