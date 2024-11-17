<?php

class ABMProducto {
    /**
     * Carga un objeto desde un arreglo asociativo.
     * @param array $param
     * @return object
     */
    private function cargarObjeto($param) {
        $objProducto = null;
        if (array_key_exists('idproducto', $param) && array_key_exists('pronombre', $param) && array_key_exists('prodetalle', $param) && array_key_exists('procantstock', $param) &&  array_key_exists('proprecio', $param) && array_key_exists('urlimagen', $param)) {
            $objProducto = new Producto();
            $objProducto->setear($param['idproducto'], $param['pronombre'], $param['prodetalle'], $param['procantstock'], $param['proprecio'], $param['urlimagen']);
            }
        return $objProducto;
    }

    /**
     * Carga un objeto con clave.
     * @param array $param
     * @return Producto|null
     */
    private function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idproducto'])) {
            $obj = new Producto();
            $obj->setear($param['idproducto'], null, null, null, null,null);
        }
        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves.
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param) {
        return isset($param['idproducto']);
    }

    /**
     * Permite insertar un nuevo objeto.
     * @param array $param
     * @return boolean
     */
    public function alta($param) {
        $resp = false;
        $param['idproducto'] = null;
        $objProducto = $this->cargarObjeto($param);
        if ($objProducto != null && $objProducto->insertar()) {
                $resp = true;
        }
        return $resp;
    }

    /**
     * Permite eliminar un objeto.
     * @param array $param
     * @return boolean
     */
    public function baja($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objProducto = $this->cargarObjetoConClave($param);
            if ($objProducto!=null && $objProducto->eliminar()){
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Permite modificar un objeto.
     * @param array $param
     * @return boolean
     */
    public function modificacion($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objProducto = $this->cargarObjeto($param);
            if ($objProducto != null && $objProducto->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Permite buscar un objeto.
     * @param array $param
     * @return array
     */
    public function buscar($param = "") {
        $where = " true ";
        if ($param <> NULL) {
            if  (isset($param['idproducto']))
            $where.=" and idproducto ='".$param['idproducto']."'";
            if  (isset($param['pronombre']))
                $where.=" and pronombre ='".$param['pronombre']."'";
            if  (isset($param['prodetalle']))
                $where.=" and prodetalle ='".$param['prodetalle']."'";
            if  (isset($param['procantstock']))
                $where.=" and procantstock ='".$param['procantstock']."'";
            if  (isset($param['proprecio']))
                $where.=" and proprecio ='".$param['proprecio']."'";
            if  (isset($param['urlimagen']))
                $where.=" and urlimagen ='".$param['urlimagen']."'";
        }
        $objProducto= new Producto();
        $arregloProductos = $objProducto->listar($where);
        return $arregloProductos;
    }


    public function obtenerStockProducto($idProducto) {
        // Buscar el producto por ID
        $productos = $this->buscar(['idproducto' => $idProducto]);
    
        // Retornar el stock si el producto existe, o 0 si no
        return count($productos) > 0 ? $productos[0]->getProCantStock() : 0;
    }
    
    

}

?>