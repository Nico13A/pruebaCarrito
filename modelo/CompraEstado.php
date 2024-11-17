<?php

class CompraEstado extends BaseDatos {
    // ATRIBUTOS
    private $idCompraEstado;
    private $objCompra;
    private $objCompraEstadoTipo;
    private $ceFechaIni;
    private $ceFechaFin;
    private $mensajeoperacion;

    public function __construct() {
        parent::__construct(); 
        $this->idCompraEstado = "";
        $this->objCompra = new Compra();
        $this->objCompraEstadoTipo = new CompraEstadoTipo();
        $this->ceFechaIni = null;
        $this->ceFechaFin = null;
    }

    public function setear($id, $compra, $compraEstadoTipo, $ceFechaIni, $ceFechaFin) {
        $this->setIdCompraEstado($id);
        $this->setObjCompra($compra);
        $this->setObjCompraEstadoTipo($compraEstadoTipo);
        $this->setCeFechaIni($ceFechaIni);
        $this->setCeFechaFin($ceFechaFin);
    }

    // SETTERS
    public function setIdCompraEstado($idCompraEstado) {
        $this->idCompraEstado = $idCompraEstado;
    }

    public function setObjCompra($compra) {
        $this->objCompra = $compra;
    }

    public function setObjCompraEstadoTipo($compraEstadoTipo) {
        $this->objCompraEstadoTipo = $compraEstadoTipo;
    }

    public function setCeFechaIni($ceFechaIni) {
        $this->ceFechaIni = $ceFechaIni;
    }

    public function setCeFechaFin($ceFechaFin) {
        $this->ceFechaFin = $ceFechaFin;
    }

    public function setMensajeOperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    // GETTERS
    public function getIdCompraEstado() {
        return $this->idCompraEstado;
    }

    public function getObjCompra() {
        return $this->objCompra;
    }

    public function getObjCompraEstadoTipo() {
        return $this->objCompraEstadoTipo;
    }

    public function getCeFechaIni() {
        return $this->ceFechaIni;
    }

    public function getCeFechaFin() {
        return $this->ceFechaFin;
    }

    public function getMensajeOperacion() {
        return $this->mensajeoperacion;
    }

    // FUNCIONES DE BASE DE DATOS
    public function cargar() {
        $resp = false;
        $sql = "SELECT * FROM compraestado WHERE idcompraestado = " . $this->getIdCompraEstado();
        
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            
            if ($res > -1) { // Verificamos que la ejecución fue exitosa
                if ($res > 0) { // Solo procesamos si hay resultados
                    $row = $this->Registro();
                    
                    // Cargar objeto Compra
                    $compra = null;
                    if ($row['idcompra'] != null) {
                        $compra = new Compra();
                        $compra->setIdCompra($row['idcompra']);
                        $compra->cargar();
                    }
    
                    // Cargar objeto CompraEstadoTipo
                    $compraEstadoTipo = null;
                    if ($row['idcompraestadotipo'] != null) {
                        $compraEstadoTipo = new CompraEstadoTipo();
                        $compraEstadoTipo->setIdCompraEstadoTipo($row['idcompraestadotipo']);
                        $compraEstadoTipo->cargar();
                    }
                    
                    // Establecer los valores en el objeto actual
                    $this->setear($row['idcompraestado'], $compra, $compraEstadoTipo, $row['cefechaini'], $row['cefechafin']);
                    $resp = true;
                }
            } else {
                $this->setMensajeOperacion("CompraEstado->cargar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraEstado->cargar: " . $this->getError());
        }
        
        return $resp;
    }
    
    public function insertar() {
        $resp = false;
        $sql = "INSERT INTO compraestado (idcompra, idcompraestadotipo, cefechaini, cefechafin) 
                VALUES (
                " . $this->getObjCompra()->getIdCompra() . ",
                " . $this->getObjCompraEstadoTipo()->getIdCompraEstadoTipo() . ",
                '" . $this->getCeFechaIni() . "',
                '" . $this->getCeFechaFin() . "')";
        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdCompraEstado($elid);
                $resp = true;
            } else {
                $this->setMensajeOperacion("CompraEstado->insertar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraEstado->insertar: " . $this->getError());
        }
        return $resp;
    }

    public function modificar() {
        $resp = false;
        $sql = "UPDATE compraestado SET 
                idcompra = " . $this->getObjCompra()->getIdCompra() . ",
                idcompraestadotipo = " . $this->getObjCompraEstadoTipo()->getIdCompraEstadoTipo() . ",
                cefechaini = '" . $this->getCeFechaIni() . "',
                cefechafin = '" . $this->getCeFechaFin() . "' 
                WHERE idcompraestado = " . $this->getIdCompraEstado();
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("CompraEstado->modificar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraEstado->modificar: " . $this->getError());
        }
        return $resp;
    }

    public function eliminar() {
        $resp = false;
        $sql = "DELETE FROM compraestado WHERE idcompraestado = " . $this->getIdCompraEstado();
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("CompraEstado->eliminar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraEstado->eliminar: " . $this->getError());
        }
        return $resp;
    }

    public function listar($parametro = "") {
        $arreglo = array(); 
        $sql = "SELECT * FROM compraestado ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) { // Verifica que la consulta no haya fallado
                if ($res > 0) { // Solo procesa si hay resultados
                    while ($row = $this->Registro()) {
                        $obj = new CompraEstado();
    
                        // Carga de objeto Compra si el id no es null
                        $objCompra = null;
                        if (!is_null($row['idcompra'])) {
                            $objCompra = new Compra();
                            $objCompra->setIdCompra($row['idcompra']);
                            $objCompra->cargar();
                        }
    
                        // Carga de objeto CompraEstadoTipo si el id no es null
                        $objCompraEstadoTipo = null;
                        if (!is_null($row['idcompraestadotipo'])) {
                            $objCompraEstadoTipo = new CompraEstadoTipo();
                            $objCompraEstadoTipo->setIdCompraEstadoTipo($row['idcompraestadotipo']);
                            $objCompraEstadoTipo->cargar();
                        }
    
                        // Configura los valores en el objeto CompraEstado
                        $obj->setear($row['idcompraestado'], $objCompra, $objCompraEstadoTipo, $row['cefechaini'], $row['cefechafin']);
                        array_push($arreglo, $obj);
                    }
                }
            } else {
                $this->setMensajeOperacion("CompraEstado->listar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraEstado->listar: " . $this->getError());
        }
        
        return $arreglo;
    }
    
}


?>