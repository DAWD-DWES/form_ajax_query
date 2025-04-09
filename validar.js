$(document).ready(function () {
    const form = $('#registro');

    form.on('submit', function (e) {
        e.preventDefault();

        const formElement = this;
        const formData = form.serializeArray();
        const data = {};

        // Convertimos a objeto clave-valor
        formData.forEach(field => {
            data[field.name] = field.value;
        });

        // Añadir manualmente el botón submit si tiene name
        const submitBtn = form.find('input[type="submit"][name]');
        if (submitBtn.length) {
            data[submitBtn.attr('name')] = submitBtn.val();
        }

        limpiarErrores(formElement);

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: $.param(data),
            dataType: 'json',
            success: function (response) {
                if (response?.errors) {
                    mostrarErrores(formElement, response.errors);
                }

                if (response?.success) {
                    console.log("Formulario válido. Enviando...");
                    formElement.submit();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
            }
        });
    });

    // Función para limpiar mensajes de error anteriores
    function limpiarErrores(form) {
        $(form).find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
        $(form).find('.invalid-feedback').text('').hide();
    }

    // Función para aplicar errores recibidos del servidor
    function mostrarErrores(form, errores) {
        for (const fieldName in errores) {
            const input = form.elements[fieldName];
            if (!input)
                continue;

            const $input = $(input);
            const $feedback = $input.closest('.input-group').find('.invalid-feedback');

            $input.addClass('is-invalid');
            if ($feedback.length) {
                $feedback.text(errores[fieldName]).show();
            }
        }
    }

    $('#registro input').on('input', function () {
        $(this).removeClass('is-invalid');
        const $feedback = $(this).closest('.input-group').find('.invalid-feedback');
        if ($feedback.length) {
            $feedback.text('').hide();
        }
    });

});
