<?php
include_once "../../../configuracion.php";

$datos = data_submitted();

if (isset($datos['idproducto'], $datos['idcompraestado'])) {
    $idProducto = $datos['idproducto'];
    $idCompraEstado = $datos['idcompraestado'];

    // Buscar el idcompra a partir del idcompraestado
    $controlCompraEstado = new AbmCompraEstado();
    $compraEstados = $controlCompraEstado->buscar(['idcompraestado' => $idCompraEstado]);

    if (count($compraEstados) > 0) {
        $compraEstado = $compraEstados[0];
        $idCompra = $compraEstado->getObjCompra()->getIdCompra(); 
        $estadoCompra = $compraEstado->getObjCompraEstadoTipo()->getIdCompraEstadoTipo(); 

        // Verificar si el estado de la compra es "carrito"
        if ($estadoCompra == 5) {  
            $controlCompraItem = new ABMCompraItem();
            $compraItems = $controlCompraItem->buscar(['idproducto' => $idProducto, 'idcompra' => $idCompra]);

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
            echo json_encode(['estado' => 'error', 'mensaje' => 'El estado de la compra no permite eliminar productos del carrito.']);
        }
    } else {
        echo json_encode(['estado' => 'error', 'mensaje' => 'Compra no encontrada.']);
    }
} else {
    echo json_encode(['estado' => 'error', 'mensaje' => 'Datos incompletos.']);
}
?>
