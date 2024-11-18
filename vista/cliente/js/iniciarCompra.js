/*
$(document).ready(function () {
    $('#boton-comprar').on('click', function () {
        // Obtener el idCompraEstado desde el input oculto
        let idCompraEstado = $('#idcompraestado').val();

        if (idCompraEstado) {
            $.ajax({
                url: './accion/accionComprar.php', 
                type: 'POST',
                data: { idcompraestado: idCompraEstado },
                dataType: 'json',
                success: function (response) {
                    if (response.estado === 'exito') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Compra realizada',
                            text: response.mensaje,
                            timer: 1500, 
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Actualizar la página
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al comprar',
                            text: response.mensaje, 
                            timer: 1500
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al procesar la compra. Intenta de nuevo más tarde.', // Sin timer para errores
                    });
                },
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Carrito vacío',
                text: 'No hay productos en el carrito para procesar.',
            });
        }
    });
});
*/

$(document).ready(function () {
    $('#boton-comprar').on('click', function () {
        // Obtener el idCompraEstado desde el input oculto
        let idCompraEstado = $('#idcompraestado').val();
        console.log(idCompraEstado);
        if (idCompraEstado) {
            $.ajax({
                url: './accion/accionComprar.php',
                type: 'POST',
                data: { idcompraestado: idCompraEstado },
                dataType: 'json',
                success: function (response) {
                    switch (response.estado) {
                        case 'exito':
                            Swal.fire({
                                icon: 'success',
                                title: '¡Compra realizada!',
                                text: response.mensaje,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); // Recargar la página para reflejar cambios
                            });
                            break;
                        case 'error':
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al procesar la compra',
                                text: response.mensaje,
                            });
                            break;
                        case 'carrito_vacio':
                            Swal.fire({
                                icon: 'warning',
                                title: 'Carrito vacío',
                                text: response.mensaje,
                            });
                            break;
                        default:
                            Swal.fire({
                                icon: 'info',
                                title: 'Atención',
                                text: 'Ocurrió un problema inesperado.',
                            });
                            break;
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'Hubo un problema al procesar la compra. Intenta de nuevo más tarde.',
                    });
                },
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Carrito vacío',
                text: 'No hay productos en el carrito para procesar.',
            });
        }
    });
});
