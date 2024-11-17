<?php
include_once("../../../configuracion.php");
$datos = data_submitted();
$objSesion = new Session();

//print_r($datos);

$usnombre = $datos['usnombre'];
$uspass = $datos['uspass'];

$resp = $objSesion->iniciar($usnombre, $uspass);
if ($resp) {
    echo "Entro";
} else {
    echo "Falso";
}
?>
