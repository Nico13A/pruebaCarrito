<?php

class CompraItem extends BaseDatos {
    // ATRIBUTOS
    private $idCompraItem;
    private $objProducto;
    private $objCompra;
    private $ciCantidad;
    private $mensajeOperacion;

    public function __construct() {
        parent::__construct();
        $this->idCompraItem = "";
        $this->objProducto = new Producto;
        $this->objCompra = new Compra;
        $this->ciCantidad = "";
        $this->mensajeOperacion = ""; 
    }

    public function setear($idCompraItem, $idProducto, $idCompra, $ciCantidad) {
        $this->setIdCompraItem($idCompraItem);
        $this->objProducto->setIdProducto($idProducto);
        $this->objCompra->setIdCompra($idCompra);
        $this->objProducto->cargar();
        $this->objCompra->cargar();
        $this->setCiCantidad($ciCantidad);
    }

    // SETTERS
    public function setIdCompraItem($idCompraItem) {
        $this->idCompraItem = $idCompraItem;
    }

    public function setObjProducto($objProducto) {
        $this->objProducto = $objProducto;
    }

    public function setObjCompra($objCompra) {
        $this->objCompra = $objCompra;
    }

    public function setCiCantidad($cantidad) {
        $this->ciCantidad = $cantidad;
    }

    public function setMensajeOperacion($mensaje) {
        $this->mensajeOperacion = $mensaje;
    }

    // GETTERS
    public function getIdCompraItem() {
        return $this->idCompraItem;
    }

    public function getObjProducto() {
        return $this->objProducto;
    }

    public function getObjCompra() {
        return $this->objCompra;
    }

    public function getCiCantidad() {
        return $this->ciCantidad;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    public function cargar() {
        $resp = false;
        $sql = "SELECT * FROM compraitem WHERE idcompraitem = " . $this->getIdCompraItem();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();
                    $this->setear($row['idcompraitem'], $row['idproducto'], $row['idcompra'], $row['cicantidad']);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("CompraItem->cargar: " . $this->getError());
        }
        return $resp;
    }

    public function insertar() {
        $resp = false;
        $sql = "INSERT INTO compraitem (idproducto, idcompra, cicantidad) VALUES (
            '" . $this->getObjProducto()->getIdProducto() . "',
            '" . $this->getObjCompra()->getIdCompra() . "',
            '" . $this->getCiCantidad() . "'
        )";
        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdCompraItem($elid);
                $resp = true;
            } else {
                $this->setMensajeOperacion("CompraItem->insertar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraItem->insertar: " . $this->getError());
        }
        return $resp;
    }

    public function modificar() {
        $resp = false;
        $sql = "UPDATE compraitem SET 
            idproducto = '" . $this->getObjProducto()->getIdProducto() . "',
            idcompra = '" . $this->getObjCompra()->getIdCompra() . "',
            cicantidad = '" . $this->getCiCantidad() . "'
            WHERE idcompraitem = '" . $this->getIdCompraItem() . "'";
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("CompraItem->modificar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraItem->modificar: " . $this->getError());
        }
        return $resp;
    }

    public function eliminar() {
        $resp = false;
        if ($this->Iniciar()) {
            $sql="DELETE FROM compraitem WHERE idcompraitem = ".$this->getIdCompraItem();
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("CompraItem->eliminar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraItem->eliminar: " . $this->getError());
        }
        return $resp;
    }

    public function listar($condicion = "") {
        $arregloCompraItem = array();
        $consultaCompraItem = "SELECT * FROM compraitem ";
        if ($condicion != "") {
            $consultaCompraItem .= ' WHERE ' . $condicion;
        }
        $consultaCompraItem .= " ORDER BY idcompraitem ";
        if ($this->Iniciar()) {
            if ($this->Ejecutar($consultaCompraItem)) {
                while ($compraItem = $this->Registro()) {
                    $objCompraItem = new CompraItem();
                    $objCompraItem->setear($compraItem["idcompraitem"], $compraItem["idproducto"], $compraItem["idcompra"], $compraItem["cicantidad"]);
                    array_push($arregloCompraItem, $objCompraItem);
                }
            } else {
                $this->setMensajeOperacion("CompraItem->listar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraItem->listar: " . $this->getError());
        }
        return $arregloCompraItem;
    }
}


?>