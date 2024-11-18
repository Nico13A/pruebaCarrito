<?php
include_once "../../../configuracion.php";

$datos = data_submitted();
$respuesta = ['estado' => 'error', 'mensaje' => 'No se pudo procesar la compra.'];

if (isset($datos['idcompraestado'])) {
    $objCompraEstado = new ABMCompraEstado();
    $arrayCompraEstado = $objCompraEstado->buscar(['idcompraestado' => $datos['idcompraestado']]);

    if (count($arrayCompraEstado) > 0) {
        $estadoActualCompra = $arrayCompraEstado[0];
        $idCompra = $estadoActualCompra->getObjCompra()->getIdCompra();

        $productosEnCarrito = obtenerProductosDeCompra($idCompra);

        if (count($productosEnCarrito) > 0) {
            if (actualizarEstadoCompra($datos, $estadoActualCompra)) {
                foreach ($productosEnCarrito as $productoEnCarrito) {
                    actualizarStockProducto($productoEnCarrito);
                }
                $respuesta = ['estado' => 'exito', 'mensaje' => 'La compra se procesó con éxito.'];
            } else {
                $respuesta['mensaje'] = 'Error al actualizar el estado de la compra.';
            }
        } else {
            $respuesta = ['estado' => 'error', 'mensaje' => 'El carrito está vacío.'];
        }
    } else {
        $respuesta['mensaje'] = 'Estado de compra no encontrado.';
    }
} else {
    $respuesta['mensaje'] = 'Datos incompletos. Falta el identificador del estado de la compra.';
}

echo json_encode($respuesta);

// **************************************** FUNCIONES ****************************************

/* Obtiene los productos asociados a una compra. */
function obtenerProductosDeCompra($idCompra) {
    $objCompraItem = new ABMCompraItem();
    $param['idcompra'] = $idCompra;
    return $objCompraItem->buscar($param);
}

/* Actualiza el estado de una compra a "iniciada". */
function actualizarEstadoCompra($datos, $estadoActualCompra) {
    $objCompraEstado = new ABMCompraEstado();
    $fechaActual = (new DateTime())->format('Y-m-d H:i:s');

    $paramCompraEstado = [
        "idcompraestado" => $datos["idcompraestado"],
        "idcompra" => $estadoActualCompra->getObjCompra()->getIdCompra(),
        "idcompraestadotipo" => 1, 
        "cefechaini" => $fechaActual,
        "cefechafin" => null,
    ];

    return $objCompraEstado->modificacion($paramCompraEstado);
}

/* Actualiza el stock de un producto según los items de la compra. */
function actualizarStockProducto($productoEnCarrito) {
    $stockActualizado = false;
    $objProducto = new ABMProducto();
    $idProducto["idproducto"] = $productoEnCarrito->getObjProducto()->getIdProducto();
    $arrayProducto = $objProducto->buscar($idProducto);

    if (count($arrayProducto) > 0) {
        $producto = $arrayProducto[0];
        $cantidadEnCarrito = $productoEnCarrito->getCiCantidad();
        $stockActual = $producto->getProCantStock();

        // Validar que haya suficiente stock antes de restar
        if ($stockActual >= $cantidadEnCarrito) {
            $nuevoStock = $stockActual - $cantidadEnCarrito;

            $paramProducto = [
                "idproducto" => $producto->getIdProducto(),
                "pronombre" => $producto->getProNombre(),
                "prodetalle" => $producto->getProDetalle(),
                "proprecio" => $producto->getProPrecio(),
                "urlimagen" => $producto->getUrlImagen(),
                "procantstock" => $nuevoStock
            ];

            $stockActualizado = $objProducto->modificacion($paramProducto);
        }
    }
    return $stockActualizado;
}


?>