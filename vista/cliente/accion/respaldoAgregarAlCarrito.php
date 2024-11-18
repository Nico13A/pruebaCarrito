<?php
// Incluir las configuraciones necesarias y clases
include_once "../../../configuracion.php";

// Recibir los datos enviados desde el formulario (AJAX)
$datos = data_submitted(); // Los datos son enviados por POST (cicantidad y idproducto)

// Verificar si los datos necesarios están presentes
if (isset($datos['idproducto'], $datos['cicantidad'])) {
    // Obtener el ID del usuario desde la sesión
    $objSession = new Session();
    $objUsuario = $objSession->getUsuario();
    $idUsuario = $objUsuario->getIdUsuario();
    $idProducto = $datos['idproducto'];
    $cantidad = $datos['cicantidad'];

    // Instanciar los objetos de control
    $controlCompra = new AbmCompra();
    $controlCompraEstado = new AbmCompraEstado();
    $controlCompraItem = new AbmCompraItem();
    $controlProducto = new ABMProducto(); // Instanciamos ABMProducto para acceder al stock

    // Verificar el stock disponible para el producto
    $stockDisponible = $controlProducto->obtenerStockProducto($idProducto);

    if ($cantidad > $stockDisponible) {
        // Si la cantidad solicitada es mayor que el stock disponible
        $response = ['estado' => 'error', 'mensaje' => 'No hay suficiente stock disponible para este producto.'];
    } else {
        // Buscar las compras del usuario
        $comprasCliente = $controlCompra->buscar(['idusuario' => $idUsuario]);

        // Si el cliente no tiene compras, se crea una nueva compra con el estado 'carrito'
        if (count($comprasCliente) == 0) {
            crearCarrito($idUsuario, $idProducto, $cantidad);
            $response = ['estado' => 'exito', 'mensaje' => 'Producto agregado al carrito.'];
        } else {
            // Si tiene compras, se busca la última compra con estado 'carrito'
            $posibleCarrito = $comprasCliente[count($comprasCliente) - 1];
            if (count($controlCompraEstado->buscar(['idcompra' => $posibleCarrito->getIdCompra()])) === 1) {
                $objCompraCarrito = $posibleCarrito;
                $colCompraItems = $controlCompraItem->buscar(['idcompra' => $objCompraCarrito->getIdCompra(), 'idproducto' => $idProducto]);

                // Si el producto ya existe en el carrito, solo se suma la cantidad
                if (count($colCompraItems) != 0) {
                    $objCompraItem = $colCompraItems[0];
                    $nuevaCantidad = $objCompraItem->getCiCantidad() + $cantidad;

                    if ($nuevaCantidad > $stockDisponible) {
                        $response = ['estado' => 'error', 'mensaje' => 'No hay suficiente stock disponible para esta cantidad.'];
                    } else {
                        $objCompraItem->setCiCantidad($nuevaCantidad);
                        // Llamamos a la función 'modificacion' del ABM de CompraItem
                        $param = [
                            'idcompraitem' => $objCompraItem->getIdCompraItem(),
                            'idproducto' => $idProducto,
                            'idcompra' => $objCompraCarrito->getIdCompra(),
                            'cicantidad' => $objCompraItem->getCiCantidad()
                        ];
                        $controlCompraItem->modificacion($param);
                        $response = ['estado' => 'exito', 'mensaje' => 'Cantidad actualizada en el carrito.'];
                    }
                } else {
                    // Si el producto no existe en el carrito, se agrega como un nuevo ítem
                    if ($cantidad <= $stockDisponible) {
                        $controlCompraItem->alta(['idproducto' => $idProducto, 'idcompra' => $objCompraCarrito->getIdCompra(), 'cicantidad' => $cantidad]);
                        $response = ['estado' => 'exito', 'mensaje' => 'Producto agregado al carrito.'];
                    } else {
                        $response = ['estado' => 'error', 'mensaje' => 'No hay suficiente stock disponible para agregar el producto al carrito.'];
                    }
                }
            } else {
                // Si no tiene un carrito activo, se crea uno nuevo
                crearCarrito($idUsuario, $idProducto, $cantidad);
                $response = ['estado' => 'exito', 'mensaje' => 'Producto agregado al carrito.'];
            }
        }
    }

    // Enviar la respuesta como JSON
    echo json_encode($response);

} else {
    // Si falta alguno de los datos necesarios, se responde con error
    echo json_encode(['estado' => 'error', 'mensaje' => 'Datos incompletos.']);
}

// Función para crear el carrito si no existe
function crearCarrito($idUsuario, $idProducto, $cantidad) {
    $controlCompra = new AbmCompra();
    $controlCompraEstado = new AbmCompraEstado();
    $controlCompraItem = new AbmCompraItem();

    // Se crea una nueva compra (carrito)
    $controlCompra->alta(['idusuario' => $idUsuario]);
    $comprasUsuario = $controlCompra->buscar(['idusuario' => $idUsuario]);
    $compra = $comprasUsuario[count($comprasUsuario) - 1];

    // Obtener la fecha de la compra (fecha de creación)
    $fechaCompra = $compra->getCoFecha();

    // Se crea el estado 'carrito' para la compra
    $controlCompraEstado->alta(['idcompra' => $compra->getIdCompra(), 'idcompraestadotipo' => 5, 'cefechaini' => $fechaCompra, 'cefechafin' => NULL]);

    // Se agrega el producto al carrito
    $controlCompraItem->alta(['idproducto' => $idProducto, 'idcompra' => $compra->getIdCompra(), 'cicantidad' => $cantidad]);
}
?>


