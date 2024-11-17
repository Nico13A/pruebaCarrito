<?php

include_once("../../../configuracion.php");

$datos = data_submitted();
$objUsuarioControl = new ABMUsuario();

$response = [];

if ($objUsuarioControl->modificacion($datos)) {
    $response['status'] = 'success';
    $response['message'] = 'Usuario modificado con éxito.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'No se pudo modificar los datos del usuario.';
}

echo json_encode($response);

?>