<?php
include_once("../../configuracion.php");

$session = new Session();
$response = array();

if ($session->cerrar()) {
    $response['status'] = 'success';
    $response['message'] = 'Sesión cerrada correctamente.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'No se pudo cerrar la sesión.';
}

echo json_encode($response);
?>