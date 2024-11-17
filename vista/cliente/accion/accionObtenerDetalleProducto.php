<?php
include_once "../../../configuracion.php";

$datos = data_submitted();

if (isset($datos['id'])) {
    $idProducto = intval($datos['id']); 
    $obj = new ABMProducto();
    $productos = $obj->buscar(['idproducto' => $idProducto]);

    if (count($productos) > 0) {
        $producto = $productos[0];
        $data = [
            'idProducto' => $producto->getIdProducto(),
            'nombre' => $producto->getProNombre(),
            'precio' => $producto->getProPrecio(),
            'imagen' => $URLIMAGEN . $producto->getUrlImagen(),
            'stock' => $producto->getProCantStock(),
            'detalle' => $producto->getProDetalle()
        ];
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Producto no encontrado']);
    }
} else {
    echo json_encode(['error' => 'ID de producto no especificado']);
}
?>




