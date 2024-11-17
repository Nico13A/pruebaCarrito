<?php

class Compra extends BaseDatos {
    // ATRIBUTOS
    private $idCompra;
    private $coFecha;
    private $objUsuario;
    private $mensajeOperacion;

    public function __construct() {
        parent::__construct();
        $this->idCompra = "";
        $this->coFecha = "";
        $this->objUsuario = new Usuario;
        $this->mensajeOperacion = "";
    }

    public function setear($idCompra, $fecha, $idUsuario) {
        $resp = false;
        $this->objUsuario->setIdUsuario($idUsuario);
        if ($this->objUsuario->cargar()) {
            $this->setIdCompra($idCompra);
            $this->setCoFecha($fecha);
            $resp = true;
        }
        return $resp;
    }

    // SETTERS
    public function setIdCompra($idCompra)
    {
        $this->idCompra = $idCompra;
    }

    public function setCoFecha($fecha)
    {
        $this->coFecha = $fecha;
    }

    public function setObjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;
    }

    public function setMensajeOperacion($mensajeOperacion) 
    {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    // GETTERS
    public function getIdCompra()
    {
        return $this->idCompra;
    }

    public function getCoFecha()
    {
        return $this->coFecha;
    }

    public function getObjUsuario()
    {
        return $this->objUsuario;
    }

    public function getMensajeOperacion()
    {
        return $this->mensajeOperacion;
    }

    public function cargar() {
        $resp = false;
        $sql = "SELECT * FROM compra WHERE idcompra = " . $this->getIdCompra();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();
                    $this->setear($row['idcompra'], $row['cofecha'], $row['idusuario']);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("Compra->cargar: " . $this->getError());
        }
        return $resp;
    }

    public function insertar() {
        $resp = false;
        $sql = "INSERT INTO compra (cofecha, idusuario) VALUES ('" . $this->getCoFecha() . "', " . $this->getObjUsuario()->getIdUsuario(). ")";
        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdCompra($elid);
                $resp =  true;
            } else {
                $this->setMensajeOperacion("Compra->insertar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("Compra->insertar: " . $this->getError());
        }
        return $resp;
    }

    public function modificar() {
        $resp = false;
        $sql = "UPDATE compra SET cofecha='" . $this->getCoFecha() . "', idusuario=" . $this->getObjUsuario()->getIdUsuario() . " WHERE idcompra=" . $this->getIdCompra();
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Compra->modificar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("Compra->modificar: " . $this->getError());
        }
        return $resp;
    }
    
    public function eliminar() {
        $resp = false;
        $sql = "DELETE FROM compra WHERE idcompra = " . $this->getIdCompra();
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp =  true;
            } else {
                $this->setMensajeOperacion("Compra->eliminar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("Compra->eliminar: " . $this->getError());
        }
        return $resp;
    }
    
    public function listar($condicion = "") {
        $arregloCompra = array();
        $consultaCompra = "SELECT * FROM compra";
        if ($condicion != "") {
            $consultaCompra .= ' WHERE ' . $condicion;
        }
        $consultaCompra .= " ORDER BY idcompra";
        if ($this->Iniciar()) {
            if ($this->Ejecutar($consultaCompra)) {
                while ($compra = $this->Registro()) {
                    $objcompra = new Compra();
                    $objcompra->setear($compra['idcompra'], $compra['cofecha'], $compra['idusuario']);
                    array_push($arregloCompra, $objcompra);
                }
            } else {
                $this->setMensajeOperacion("Compra->listar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("Compra->listar: " . $this->getError());
        }
        return $arregloCompra;
    }
    
}

?>