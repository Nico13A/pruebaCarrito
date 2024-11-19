<?php
$Titulo = "Code Wear - Compras";
include_once "../estructura/cabecera.php";
$objSession = new Session();
$objUsuario = $objSession->getUsuario();
$idUsuario = $objUsuario->getIdUsuario();

$controlCompra = new AbmCompra();
$controlCompraEstado = new AbmCompraEstado();
$controlCompraItem = new AbmCompraItem();

$comprasUsuario = $controlCompra->buscar(['idusuario' => $idUsuario]);

$respuesta="";
$cant=count($comprasUsuario);

if ($cant>0) {
    $detallesCompra = []; // Inicializa el array
    foreach ($comprasUsuario as $compra) {
        $idCompra=$compra->getIdCompra();
        $objCompraEdo= $controlCompraEstado->buscar(['idcompra' => $idCompra]);
        foreach ($objCompraEdo as $edo) {
            $fechaFin=$edo->getCeFechaFin();
            switch ($edo->getObjCompraEstadoTipo()->getIdCompraEstadoTipo()) {
                case '1':

            $detallesCompra[] = [
            "Id de compra" => $idCompra, 
                "Estado" => "Iniciada",
                "Fecha de inicio" => $edo->getCeFechaIni(), 
                "Fecha fin" => $fechaFin 
            ];

                    break;
                case '2':
                    $detallesCompra[] = [
                        "Id de compra" => $idCompra, 
                        "Estado" => "Aceptada",
                        "Fecha de inicio" => $edo->getCeFechaIni(), 
                        "Fecha fin" => $fechaFin 
                    ];
                    break;
                
                case '3':
                    $detallesCompra[]= [
                        "Id de compra" => $idCompra, 
                        "Estado" => "enviada",
                        "Fecha de inicio" => $edo->getCeFechaIni(), 
                        "Fecha fin" => $fechaFin 
                    ];
                    break;
                    case '4':
                        $detallesCompra[] = [
                            "Id de compra" => $idCompra, 
                            "Estado" => "Cancelada",
                            "Fecha de inicio" => $edo->getCeFechaIni(), 
                            "Fecha fin" => $fechaFin 
                        ];
                        break;
                
                default:
                    
                    break;
            }
        
        }
    }
    
}
?>
<!-- Modal para mostrar compras -->
<h2 class="display-5 fw-normal text-center py-4">Detalles de compras</h2>
        <?php
            if (count($detallesCompra)>0) {
                ?>
            <div class="container py-2 mb-4 contenedorTabla">
            <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th class="align-middle" scope="col">ID Compra</th>
                <th class="align-middle" scope="col">Estado</th>
                <th class="align-middle" scope="col">Fecha de inicio</th>
                <th class="align-middle" scope="col">Fecha fin</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($detallesCompra as $detalle) {
                
                    echo '<tr>
                    <th class="align-middle" scope="row">' . $detalle["Id de compra"] . '</th>
                    <td class="align-middle">' . $detalle["Estado"] . '</td>
                    <td class="align-middle">' . $detalle["Fecha de inicio"] . '</td>
                    <td class="align-middle">' . $detalle["Fecha fin"] . '</td></tr> ';
                }
            }
            else {
                echo "<h2>No tiene compras realizadas</h2>";
            }
            ?>
            
        </tbody>
    </table>
   </div>


<?php
include_once "../estructura/pie.php";
?>

