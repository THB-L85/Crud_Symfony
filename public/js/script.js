$(document).ready(function() {
    $('.btn-danger').on('click', function() {
        // Obtener los valores de los data-attributes
        var pregunta = $(this).data('pregunta');
        var ruta = $(this).data('ruta');

        // Mostrar el cuadro de confirmación con SweetAlert
        Swal.fire({
            title: pregunta,
            icon: 'warning',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'No',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Redireccionar a la ruta proporcionada
                window.location.href = ruta;
            }
        });
    });
});



