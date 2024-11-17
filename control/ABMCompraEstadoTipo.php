<?php

class ABMCompraEstadoTipo {
    /**
     * Carga un objeto desde un arreglo asociativo.
     * @param array $param
     * @return object
     */
    private function cargarObjeto($param) {
        $objCompraEstadoTipo = null;
        if (array_key_exists('idcompraestadotipo',$param) && array_key_exists('cetdescripcion',$param) && array_key_exists('cetdetalle',$param)) {
            $objCompraEstadoTipo = new CompraEstadoTipo();
            $objCompraEstadoTipo->setear($param['idcompraestadotipo'], $param['cetdescripcion'], $param['cetdetalle']);
        }
        return $objCompraEstadoTipo;
    }

    /**
     * Carga un objeto con clave.
     * @param array $param
     * @return CompraEstadoTipo|null
     */
    private function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idcompraestadotipo'])) {
            $obj = new CompraEstadoTipo();
            $obj->setear($param['idcompraestadotipo'], null, null);
        }
        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves.
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param) {
        return isset($param['idcompraestadotipo']);
    }

    /**
     * Permite insertar un nuevo objeto.
     * @param array $param
     * @return boolean
     */
    public function alta($param) {
        $resp = false;
        $param['idcompraestadotipo'] = null;
        $obj = $this->cargarObjeto($param);
        if ($obj != null && $obj->insertar()) {
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
            $obj = $this->cargarObjetoConClave($param);
            if ($obj !=null && $obj->eliminar()) {
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
            $obj = $this->cargarObjeto($param);
            if ($obj != null && $obj->modificar()) {
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
    public function buscar($param) {
        $where = " true ";
        if ($param <> NULL) {
            if  (isset($param['idcompraestadotipo']))
                $where.=" and idcompraestadotipo ='".$param['idcompraestadotipo']."'";
            if  (isset($param['cetdescripcion']))
                $where.=" and cetdescripcion ='".$param['cetdescripcion']."'";
            if  (isset($param['cetdetalle']))
                $where.=" and cetdetalle ='".$param['cetdetalle']."'";
        }

        $obj = new CompraEstadoTipo();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }
}

?>