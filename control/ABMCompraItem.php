<?php

class ABMCompraItem {
    /**
     * Carga un objeto desde un arreglo asociativo.
     * @param array $param
     * @return object
     */
    private function cargarObjeto($param) {
        $objCompraItem = null;
        if (array_key_exists('idcompraitem', $param) and array_key_exists('idproducto', $param) and array_key_exists('idcompra', $param) and array_key_exists('cicantidad', $param)) {
            $objCompraItem = new CompraItem();
            $objCompraItem->setear($param['idcompraitem'], $param['idproducto'], $param['idcompra'], $param['cicantidad']);
        }
        return $objCompraItem;
    }

    /**
     * Carga un objeto con clave.
     * @param array $param
     * @return CompraItem|null
     */
    /*
    private function cargarObjetoConClave($param) {
        $objCompraItem = null;
        if (isset($param['idcompraitem']) ){
            $objCompraItem = new CompraItem();
            $objCompraItem->setear($param['idcompraitem'], null, null, null);
        }
        return $objCompraItem;
    }
    */
    private function cargarObjetoConClave($param){
        $obj = null;
        if( isset($param['idcompraitem']) ){
            $obj = new CompraItem();
            $obj->setIdCompraItem($param['idcompraitem']);
            $obj->cargar();
        }
        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves.
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param) {
        $resp = false;
        if (isset($param['idcompraitem'])) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Permite insertar un nuevo objeto.
     * @param array $param
     * @return boolean
     */
    public function alta($param) {
        $resp = false;
        $param['idcompraitem'] = null;
        $objCompraItem = $this->cargarObjeto($param);
        if ($objCompraItem != null && $objCompraItem->insertar()) {
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
            $objCompraItem = $this->cargarObjetoConClave($param);
            if ($objCompraItem != null and $objCompraItem->eliminar()) {
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
            $objCompraItem = $this->cargarObjeto($param);
            if ($objCompraItem != null && $objCompraItem->modificar()) {
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
            if  (isset($param['idcompraitem']))
                $where.=" and idcompraitem ='".$param['idcompraitem']."'";
            if  (isset($param['idproducto']))
                $where.=" and idproducto ='".$param['idproducto']."'";
            if  (isset($param['idcompra']))
                $where.=" and idcompra ='".$param['idcompra']."'";
            if  (isset($param['cicantidad']))
                $where.=" and cicantidad ='".$param['cicantidad']."'";
        }
        $objCompraItem = new CompraItem();
        $arregloCompraItem = $objCompraItem->listar($where);
        return $arregloCompraItem;
    }

}

?>