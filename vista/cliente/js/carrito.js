$(document).ready(function () {
    $('.carrito').on('click', function () {
        // Hacer la solicitud AJAX para obtener los productos del carrito
        $.ajax({
            url: './accion/accionObtenerCarrito.php',
            type: 'POST',
            success: function (response) {
                let data = JSON.parse(response);

                if (data.estado === 'exito') {
                    let carritoHTML = '';
                    let total = 0; // Variable para el total de la compra

                    // Variable para almacenar el idCompraEstado
                    let idCompraEstado = null;

                    // Iterar sobre los carritos
                    if (data.carritos && data.carritos.length > 0) {
                        let carrito = data.carritos[0]; 
                        idCompraEstado = carrito.idcompraestado; 

                        carrito.productos.forEach(function (producto) {
                            // Calcular el subtotal (precio * cantidad)
                            let subtotal = producto.precio * producto.cantidad;
                            total += subtotal; // Acumulamos el total

                            // Agregar fila a la tabla
                            carritoHTML += `
                                <tr data-id="${producto.idproducto}">
                                    <td><img src="${producto.imagen}" alt="${producto.nombre}" class="img-fluid" style="max-width: 50px;"></td>
                                    <td>${producto.nombre}</td>
                                    <td>${producto.cantidad}</td>
                                    <td>$${producto.precio}</td>
                                    <td>$${subtotal}</td>
                                    <td><button class="btn btn-danger btn-sm delete-item">Eliminar</button></td>
                                </tr>
                            `;
                        });
                    }

                    // Limpiar el contenido anterior y agregar los nuevos productos
                    $('#cart-items').html(carritoHTML);

                    // Eliminar fila de total anterior
                    $('#cart-items tr.total-row').remove();

                    // Mostrar el nuevo total en la parte inferior del modal
                    $('#cart-items').append(`
                        <tr class="total-row">
                            <td colspan="4" class="text-end fw-bold">Total:</td>
                            <td>$${total}</td>
                        </tr>
                    `);

                    // Mostrar el modal del carrito
                    $('#cartModal').modal('show');

                    // Asociar el evento de eliminación dinámicamente
                    $('.delete-item').on('click', function () {
                        // Obtener el idProducto desde el atributo data-id
                        let idProducto = $(this).closest('tr').data('id');
                        let idCompraEstado = $('#idcompraestado').val();

                        // Hacer la solicitud AJAX para eliminar el ítem del carrito
                        $.ajax({
                            url: './accion/accionEliminarCarrito.php',
                            type: 'POST',
                            data: { idproducto: idProducto, idcompraestado: idCompraEstado },
                            dataType: 'json',
                            success: function (response) {
                                if (response.estado === 'exito') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Producto eliminado',
                                        text: response.mensaje,
                                    });

                                    // Recargar el carrito después de eliminar un producto
                                    $('.carrito').click();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error al eliminar',
                                        text: response.mensaje,
                                    });
                                }
                            },
                            error: function () {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Hubo un problema al eliminar el producto. Intenta de nuevo más tarde.',
                                });
                            }
                        });
                    });

                    // Guardar el idCompraEstado en un atributo oculto
                    $('#idcompraestado').val(idCompraEstado);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Carrito vacío',
                        text: 'No tienes productos en el carrito.',
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al obtener el carrito. Intenta de nuevo más tarde.',
                });
            }
        });
    });
});

