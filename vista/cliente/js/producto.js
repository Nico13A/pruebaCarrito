// Función para solicitar datos al servidor y mostrar modal con detalle del producto cliqueado.
$('.botonVer').on('click', function() {
    let idProducto = $(this).data('id');

    $.ajax({
        url: './accion/accionObtenerDetalleProducto.php',
        type: 'POST',
        data: { id: idProducto },
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                // Actualiza el contenido del modal con los datos del producto.
                $('#idproducto').val(data.idProducto);
                $('#product-name').text(data.nombre);
                $('#product-details').html('<span class="propiedadProducto">Descripción:</span> ' + data.detalle);
                $('#product-price').html('<span class="propiedadProducto">Precio:</span> $' + data.precio);
                $('#product-image').attr('src', data.imagen);
                $('#product-stock').html('<span class="propiedadProducto">Stock disponible:</span> ' + data.stock);

                // Verifica el stock disponible
                if (data.stock > 0) {
                    $('#cicantidad').attr('max', data.stock); // Limita la cantidad al stock disponible
                    $('#cicantidad').prop('disabled', false); // Habilita el campo de cantidad
                    $('#agregar-al-carrito').prop('disabled', false); // Habilita el botón de agregar al carrito
                    $('#product-stock').removeClass('text-danger');
                } else {
                    $('#cicantidad').val(0); // Establece la cantidad a 0
                    $('#cicantidad').prop('disabled', true); // Deshabilita el campo de cantidad
                    $('#agregar-al-carrito').prop('disabled', true); // Deshabilita el botón de agregar al carrito
                    $('#product-stock').addClass('text-danger').text('¡Sin stock!'); // Muestra mensaje de sin stock
                }

                // Muestra el modal.
                $('#productoModal').modal('show');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al obtener los detalles del producto.',
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
});

