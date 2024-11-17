<?php
include_once "../../../configuracion.php";

$datos = data_submitted();

if (isset($datos['idproducto'])) {
    $idProducto = $datos['idproducto'];

    // Buscar el item en el carrito
    $controlCompraItem = new ABMCompraItem();
    $compraItems = $controlCompraItem->buscar(['idproducto' => $idProducto]);

    if (count($compraItems) > 0) {
        $objCompraItem = $compraItems[0];
        $param['idcompraitem'] = $objCompraItem->getIdCompraItem();

        if (!empty($param['idcompraitem'])) {
            $controlCompraItem->baja($param);  // Eliminar el producto del carrito
            echo json_encode(['estado' => 'exito', 'mensaje' => 'Producto eliminado del carrito.']);
        } else {
            echo json_encode(['estado' => 'error', 'mensaje' => 'ID de compra item no encontrado.']);
        }
    } else {
        echo json_encode(['estado' => 'error', 'mensaje' => 'Producto no encontrado en el carrito.']);
    }
} else {
    echo json_encode(['estado' => 'error', 'mensaje' => 'Datos incompletos.']);
}


?>
