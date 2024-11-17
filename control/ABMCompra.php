<?php

class ABMCompra {
    /**
     * Carga un objeto desde un arreglo asociativo.
     * @param array $param
     * @return object
     */
    /*
    private function cargarObjeto($param) {
        $objCompra = null;
        if (array_key_exists('idcompra', $param) && array_key_exists('cofecha', $param) && array_key_exists('idusuario', $param)) {
            $objCompra = new Compra();
            $objUsuario = null;
            if ($param['idusuario'] != null) {
                $objUsuario = new Usuario();
                $objUsuario->setIdUsuario($param['idusuario']);
                $objUsuario->cargar();
            }
            $objCompra->setear($param['idcompra'], $param['cofecha'], $objUsuario);
        }
        return $objCompra;
    }*/
    private function cargarObjeto($param)
    {
        $objCompra = null;

        if (array_key_exists('idcompra', $param) and array_key_exists('cofecha', $param) and array_key_exists('idusuario', $param)) {
            $objCompra = new Compra();
            if(!$objCompra->setear($param['idcompra'], $param['cofecha'], $param['idusuario'])){
                $objCompra = null;
            }
        }
        return $objCompra;
    }

    /**
     * Carga un objeto con clave.
     * @param array $param
     * @return Compra|null
     */
    private function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idcompra'])) {
            $obj = new Compra();
            $obj->setear($param['idcompra'], null, null);
        }
        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves.
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param) {
        return isset($param['idcompra']);
    }

    /**
     * Permite insertar un nuevo objeto.
     * @param array $param
     * @return boolean
     */
    public function alta($param) {
        $resp = false;
        $param['idcompra'] = null;
        $fecha = new DateTime();
        $fechaStamp = $fecha->format('Y-m-d H:i:s');
        $param['cofecha'] = $fechaStamp;
        $objCompra = $this->cargarObjeto($param);
        if ($objCompra != null && $objCompra->insertar()) {
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
            $objCompra = $this->cargarObjetoConClave($param);
            if ($objCompra!=null && $objCompra->eliminar()) {
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
            $objCompra = $this->cargarObjeto($param);
            if ($objCompra != null && $objCompra->modificar()) {
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
            if  (isset($param['idcompra']))
                $where.=" and idcompra ='".$param['idcompra']."'";
            if  (isset($param['cofecha']))
                $where.=" and cofecha ='".$param['cofecha']."'";
            if  (isset($param['idusuario']))
                $where.=" and idusuario ='".$param['idusuario']."'";
        }
        $objCompra = new Compra();
        $arregloCompras = $objCompra->listar($where);
        return $arregloCompras;
    }

}

?>