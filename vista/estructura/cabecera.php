<?php
include_once("../../configuracion.php");

// Inicializamos la sesión
$objSession = new Session();

// Inicializamos los menús como un array vacío
$menues = [];
$menuPublico = [
    ["link" => "../../index.php", "nombre" => "Home"],
    ["link" => "../cliente/productos.php", "nombre" => "Nuestros productos"],
    ["link" => "../sesion/iniciarSesion.php", "nombre" => "Iniciar Sesión"]
];

// Verificamos si la sesión está activa
if ($objSession->validar()) {

    // Obtenemos los roles del usuario actual
    $rolesUsuario = $objSession->getRol();

    // Inicializamos los objetos para manejar los roles y menús
    $objMenuRol = new ABMMenuRol();
    
    // Obtenemos los menús asociados al rol del usuario
    if (!empty($rolesUsuario)) {
        $menues = $objMenuRol->obtenerMenuesPorRol($rolesUsuario[0]->getObjRol()->getIdRol());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($Titulo) ? $Titulo : "Inicio"; ?></title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="stylesheet" href="../css/producto.css">

    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../jQuery/jquery-3.6.1.min.js"></script>
    <!-- Incluir la biblioteca CryptoJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
    <!-- Incluir la biblioteca Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand link-dark fs-3 fw-bold" href="../../index.php">CODE WEAR</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <!-- Menú público (si el usuario no está logueado) -->
                    <?php if (!$objSession->validar()) { ?>
                        <?php foreach ($menuPublico as $menu) { ?>
                            <li class="nav-item">
                                <a href="<?php echo $menu['link']; ?>" class="nav-link link-secondary fs-5"><?php echo $menu['nombre']; ?></a>
                            </li>
                        <?php } ?>
                    <?php } else { ?>
                        <!-- Menú privado (si el usuario está logueado) -->
                        <?php foreach ($menues as $objMenu) { ?>
                            <?php if ($objMenu->getMeDeshabilitado() == null) { ?>
                                <li class="nav-item">
                                    <a href="<?php echo $objMenu->getMeDescripcion(); ?>" class="nav-link link-secondary fs-5"><?php echo $objMenu->getMeNombre(); ?></a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                        <li class="nav-item">
                            <!-- <a href="../accion/cerrarSesion.php" class="nav-link link-secondary fs-5">Salir</a> -->
                            <button id="cierreSesion" class="btn btn-link nav-link link-secondary fs-5">Salir</button>
                        </li>        
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
    