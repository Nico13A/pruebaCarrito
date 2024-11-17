$(document).ready(function () {
    $("#formCarrito").submit(function (event) {
        event.preventDefault(); // Evita que el formulario se envíe de manera tradicional.

        // Captura los datos del formulario.
        const formData = {
            cicantidad: $("#cicantidad").val(),
            idproducto: $("#idproducto").val(),
        };

        // Realiza la solicitud AJAX.
        $.ajax({
            url: './accion/accionAgregarAlCarrito.php', // Cambia por la URL de tu archivo PHP.
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.estado === 'exito') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Producto agregado!',
                        text: 'El producto se añadió correctamente al carrito.',
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.mensaje || 'Ocurrió un problema al agregar el producto.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error("Error:", textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error inesperado',
                    text: 'No se pudo procesar la solicitud.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});
