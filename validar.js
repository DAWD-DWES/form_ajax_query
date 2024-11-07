$(document).ready(function () {
    $('#registro').submit(validaForm);
});
function validaForm(e) {
    e.preventDefault();
    var form = this;

    // Usar el método de jQuery para crear el objeto FormData
    var data = $(this).serialize();
    data += '&enviar=' + encodeURIComponent($('input[name="enviar"]').val());

    // Usar jQuery.ajax() para enviar los datos
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
        dataType: 'json', // Esperamos JSON de vuelta
        success: function (response) {
            $(form).removeClass('was-validated');
            // Ajustar la validación en cada campo específico
            $.each(form.elements, function () {
                var input = this;
                var feedback = $(input).next('.invalid-feedback');
                if (response.errors[input.name]) {
                    $(feedback).text(response.errors[input.name]);
                    $(feedback).show();
                    $(input).addClass('is-invalid').removeClass('is-valid');
                } else {
                    $(input).removeClass('is-invalid').addClass('is-valid');
                    $(feedback).text('').hide();
                }
            });
            if (response.success) {
                // Si quieres hacer algo al tener éxito, como redireccionar o mostrar un mensaje
                // alert('Registro completado con éxito.');
                form.submit(); // O puedes redireccionar usando window.location.href a otra URL
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error en la red o en el servidor: ' + textStatus + ', ' + errorThrown);
        }
    });
}
