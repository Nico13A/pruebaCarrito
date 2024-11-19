<?php
include_once "../../../configuracion.php";

$datos = data_submitted(); // Los datos son (cicantidad y idproducto)

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
    $controlProducto = new ABMProducto(); 

    // Verificar el stock disponible para el producto
    $stockDisponible = $controlProducto->obtenerStockProducto($idProducto);

    if ($cantidad > $stockDisponible) {
        // Si la cantidad solicitada es mayor que el stock disponible
        $response = ['estado' => 'error', 'mensaje' => 'No hay suficiente stock disponible para este producto.'];
    } else {
        // Buscar las compras del usuario
        $comprasCliente = $controlCompra->buscar(['idusuario' => $idUsuario]);

        // Variable para determinar si hay un carrito activo
        $carritoActivo = null;

        foreach ($comprasCliente as $compra) {
            // Verificar si la compra tiene estado 'carrito'
            $compraEstado = $controlCompraEstado->buscar(['idcompra' => $compra->getIdCompra()]);
            if (count($compraEstado) > 0) {
                $ultimoEstado = $compraEstado[0];
                if ($ultimoEstado->getObjCompraEstadoTipo()->getIdCompraEstadoTipo() == 5) { // 5 es el estado 'carrito'
                    $carritoActivo = $compra;
                    break;
                }
            }
        }

        // Si no hay un carrito activo, crear uno nuevo
        if ($carritoActivo === null) {
            crearCarrito($idUsuario, $idProducto, $cantidad);
            $response = ['estado' => 'exito', 'mensaje' => 'Producto agregado al carrito.'];
        } else {
            // Si hay un carrito activo, agrego productos
            $objCompraCarrito = $carritoActivo;
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