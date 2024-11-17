<?php

class Producto extends BaseDatos {
    private $idProducto;
    private $proNombre;
    private $proDetalle;
    private $proCantStock;
    private $proPrecio;
    private $urlImagen;
    private $mensajeOperacion;

    public function __construct() {
        parent::__construct();
        $this->idProducto = "";
        $this->proNombre = "";
        $this->proDetalle = "";
        $this->proCantStock = "";
        $this->proPrecio = "";
        $this->urlImagen = "";
        $this->mensajeOperacion = "";
    }

    public function setear($idProducto, $proNombre, $proDetalle, $proCantStock, $proPrecio, $urlImagen) {
        $this->setIdProducto($idProducto);
        $this->setProNombre($proNombre);
        $this->setProDetalle($proDetalle);
        $this->setProCantStock($proCantStock);
        $this->setProPrecio($proPrecio);
        $this->setUrlImagen($urlImagen);
    }

    // SETTERS
    public function setIdProducto($idProducto) {
        $this->idProducto = $idProducto;
    }

    public function setProNombre($proNombre) {
        $this->proNombre = $proNombre;
    }

    public function setProDetalle($proDetalle) {
        $this->proDetalle = $proDetalle;
    }

    public function setProCantStock($proCantStock) {
        $this->proCantStock = $proCantStock;
    }

    public function setProPrecio($proPrecio) {
        $this->proPrecio = $proPrecio;
    }

    public function setUrlImagen($urlImagen) {
        $this->urlImagen = $urlImagen;
    }

    public function setMensajeOperacion($mensajeOperacion) {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    // GETTERS
    public function getIdProducto() {
        return $this->idProducto;
    }

    public function getProNombre() {
        return $this->proNombre;
    }

    public function getProDetalle() {
        return $this->proDetalle;
    }

    public function getProCantStock() {
        return $this->proCantStock;
    }

    public function getProPrecio() {
        return $this->proPrecio;
    }

    public function getUrlImagen() {
        return $this->urlImagen;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    public function __toString() {
        return "ID del producto: " . $this->getIdProducto() . "\nNombre: " . $this->getProNombre() . "\nDetalle: " . $this->getProDetalle() . "\nStock: " . $this->getProCantStock() . "\nPrecio: " . $this->getProPrecio() . "\nURL Imagen: " . $this->getUrlImagen() . "\n";
    }

    public function cargar() {
        $resp = false;
        $sql = "SELECT * FROM producto WHERE idproducto = " . $this->getIdProducto();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();
                    $this->setear($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock'], $row['proprecio'], $row['urlimagen']);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("Producto->cargar: " . $this->getError());
        }
        return $resp;
    }

    public function insertar() {
        $resp = false;
        $sql = "INSERT INTO producto (pronombre, prodetalle, procantstock, proprecio, urlimagen) VALUES (
        '" . $this->getProNombre() . "',
        '" . $this->getProDetalle() . "',
        '" . $this->getProCantStock() . "',
        '" . $this->getProPrecio() . "',
        '" . $this->getUrlImagen() . "')";
        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdProducto($elid);
                $resp = true;
            } else {
                $this->setMensajeOperacion("Producto->insertar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("Producto->insertar: " . $this->getError());
        }
        return $resp;
    }

    public function modificar() {
        $resp = false;
        $sql = "UPDATE producto SET pronombre='" . $this->getProNombre() . "', prodetalle='" . $this->getProDetalle() . "', procantstock='" . $this->getProCantStock() . "', proprecio='" . $this->getProPrecio() . "', urlimagen='" . $this->getUrlImagen() . "' WHERE idproducto='" . $this->getIdProducto() . "'";
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Producto->modificar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("Producto->modificar: " . $this->getError());
        }
        return $resp;
    }

    public function eliminar() {
        $resp = false;
        if ($this->Iniciar()) {
            $sql = "DELETE FROM producto WHERE idproducto = '" . $this->getIdProducto() . "'";
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Producto->eliminar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("Producto->eliminar: " . $this->getError());
        }
        return $resp;
    }

    public function listar($condicion = "") {
        $arregloProductos = array();
        $sql = "SELECT * FROM producto ";
        if ($condicion != "") {
            $sql = $sql . ' WHERE ' . $condicion;
        }
        $sql .= " ORDER BY idproducto ";
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                while ($producto = $this->Registro()) {
                    $objProducto = new Producto();
                    $objProducto->setear($producto['idproducto'], $producto['pronombre'], $producto['prodetalle'], $producto['procantstock'], $producto['proprecio'], $producto['urlimagen']);
                    array_push($arregloProductos, $objProducto);
                }
            } else {
                $this->setMensajeOperacion("Producto->listar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("Producto->listar: " . $this->getError());
        }
        return $arregloProductos;
    }
}


?>