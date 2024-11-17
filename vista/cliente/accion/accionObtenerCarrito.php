<?php
include_once "../../../configuracion.php";

$objSession = new Session();
$objUsuario = $objSession->getUsuario();
$idUsuario = $objUsuario->getIdUsuario();

// Verificar que el usuario está logueado
if ($idUsuario) {
    // Buscar los productos en el carrito del usuario
    $controlCompraItem = new AbmCompraItem();
    $itemsCarrito = $controlCompraItem->buscar(['idusuario' => $idUsuario]);

    if (count($itemsCarrito) > 0) {
        $productos = [];
        foreach ($itemsCarrito as $item) {
            $producto = $item->getObjProducto();  
            $productos[] = [
                'idproducto' => $producto->getIdProducto(),
                'nombre' => $producto->getProNombre(),
                'precio' => $producto->getProPrecio(),
                'cantidad' => $item->getCiCantidad(),
                'imagen' => $URLIMAGEN . $producto->getUrlImagen()
            ];
        }

        // Responder con los productos en el carrito
        echo json_encode([
            'estado' => 'exito',
            'productos' => $productos
        ]);
    } else {
        // No hay productos en el carrito
        echo json_encode(['estado' => 'error', 'mensaje' => 'Carrito vacío.']);
    }
} else {
    echo json_encode(['estado' => 'error', 'mensaje' => 'Usuario no logueado.']);
}
?>
