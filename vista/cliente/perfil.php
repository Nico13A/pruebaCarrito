<?php
$Titulo = "Code Wear - Perfil usuario";
include_once "../estructura/cabecera.php";

$datos = data_submitted();

if (!$objSession->validar()) {
    header("Location: ../sesion/iniciarSesion.php");
    exit; 
}

// Obtener el usuario logueado
$usuario = $objSession->getUsuario();

// Verificar si se obtuvo el usuario correctamente
if ($usuario == null) {
    echo "<p class='text-danger text-center'>Error: No se pudo obtener los datos del usuario.</p>";
    exit;
}

$idUsuario = $usuario->getIdUsuario();

$obj = new ABMUsuario();

$datosUsuario = $obj->buscar(["idusuario" => $idUsuario]);

// Verificar si se encontraron datos del usuario
if (count($datosUsuario) == 0) {
    echo "<p class='text-danger text-center'>Error: Usuario no encontrado.</p>";
    exit;
}

$objUsuario = $datosUsuario[0];

?>

<h2 class="display-5 fw-normal text-center py-4">Datos personales</h2>
<div class="container py-2 mb-4 contenedorTabla">
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th class="align-middle" scope="col">ID</th>
                <th class="align-middle" scope="col">Usuario</th>
                <th class="align-middle" scope="col">Email</th>
                <th class="align-middle" scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="align-middle" scope="row"><?php echo $objUsuario->getIdUsuario() ?></th>
                <td class="align-middle"><?php echo $objUsuario->getUsNombre() ?></td>
                <td class="align-middle"><?php echo $objUsuario->getUsMail() ?></td>
                <td class="align-middle">
                    <input class="btn btn-secondary me-3" type="button" name="botonContrasenia"  id="botonContrasenia" value="Cambiar contraseña">
                    <input class="btn btn-secondary me-3" type="button" name="botonEmail"  id="botonEmail" value="Cambiar email">
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php include_once "modales.php"; ?>
<script src="./js/editarPerfil.js"></script>
<?php
include_once "../estructura/pie.php";
?>


