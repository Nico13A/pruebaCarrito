<?php

class CompraEstadoTipo extends BaseDatos {
    // ATRIBUTOS
    private $idCompraEstadoTipo;
    private $cetDescripcion;
    private $cetDetalle;
    private $mensajeOperacion;

    public function __construct() {
        parent::__construct();
        $this->idCompraEstadoTipo = "";
        $this->cetDescripcion = "";
        $this->cetDetalle = "";
        $this->mensajeOperacion = "";
    }

    public function setear($id, $descripcion, $detalle) {
        $this->setIdCompraEstadoTipo($id);
        $this->setCetDescripcion($descripcion);
        $this->setCetDetalle($detalle);
    }

    // SETTERS
    public function setIdCompraEstadoTipo($idCompraEstadoTipo) {
        $this->idCompraEstadoTipo = $idCompraEstadoTipo;
    }

    public function setCetDescripcion($cetDescripcion) {
        $this->cetDescripcion = $cetDescripcion;
    }

    public function setCetDetalle($cetDetalle) {
        $this->cetDetalle = $cetDetalle;
    }

    public function setMensajeOperacion($mensajeOperacion) {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    // GETTERS
    public function getIdCompraEstadoTipo() {
        return $this->idCompraEstadoTipo;
    }

    public function getCetDescripcion() {
        return $this->cetDescripcion;
    }

    public function getCetDetalle() {
        return $this->cetDetalle;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    public function cargar() {
        $resp = false;
        $sql = "SELECT * FROM compraestadotipo WHERE idcompraestadotipo = " . $this->getIdCompraEstadoTipo();
    
        if ($this->Iniciar()) { // Usamos 'this' porque 'Iniciar' es un método de la clase base.
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) { // Verificamos si la consulta devolvió resultados
                    $row = $this->Registro(); // Usamos 'Registro' para obtener la fila de datos
                    $this->setear($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("CompraEstadoTipo->cargar: " . $this->getError());
        }
        
        return $resp;
    }
    

    public function insertar() {
        $resp = false;
        $sql = "INSERT INTO compraestadotipo (cetdescripcion, cetdetalle) VALUES ('" . $this->getCetDescripcion() . "', '" . $this->getCetDetalle() . "');";
        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdCompraEstadoTipo($elid);
                $resp = true;
            } else {
                $this->setMensajeOperacion("CompraEstadoTipo->insertar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraEstadoTipo->insertar: " . $this->getError());
        }
        return $resp;
    }

    public function modificar() {
        $resp = false;
        $sql = "UPDATE compraestadotipo SET cetdescripcion='" . $this->getCetDescripcion() . "', cetdetalle='" . $this->getCetDetalle() . "' WHERE idcompraestadotipo='" . $this->getIdCompraEstadoTipo() . "'";
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("CompraEstadoTipo->modificar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraEstadoTipo->modificar: " . $this->getError());
        }
        return $resp;
    }

    public function eliminar() {
        $resp = false;
        $sql = "DELETE FROM compraestadotipo WHERE idcompraestadotipo=" . $this->getIdCompraEstadoTipo();
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("CompraEstadoTipo->eliminar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraEstadoTipo->eliminar: " . $this->getError());
        }
        return $resp;
    }

    public function listar($parametro = "") {
        $arreglo = array();
        $sql = "SELECT * FROM compraestadotipo ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        
        if ($this->Iniciar()) { // Usamos 'this' para acceder a la clase base
            $res = $this->Ejecutar($sql);
            
            if ($res > -1) { // Verificamos que la consulta se ejecutó correctamente
                if ($res > 0) { // Verificamos que hay resultados (mayor que 0)
                    while ($row = $this->Registro()) { // Usamos 'this->Registro()' para obtener la fila
                        $obj = new CompraEstadoTipo();
                        $obj->setear($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
                        array_push($arreglo, $obj);
                    }
                } else {
                    $this->setMensajeOperacion("CompraEstadoTipo->listar: No se encontraron resultados.");
                }
            } else {
                $this->setMensajeOperacion("CompraEstadoTipo->listar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraEstadoTipo->listar: " . $this->getError());
        }
    
        return $arreglo;
    }
    
}

?>