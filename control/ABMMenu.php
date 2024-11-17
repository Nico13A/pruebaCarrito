<?php

class ABMMenu {
    /**
     * Carga un objeto desde un arreglo asociativo.
     * @param array $param
     * @return Menu|null
     */
    private function cargarObjeto($param) {
        $obj = null; 
        if(array_key_exists('idmenu', $param) and array_key_exists('menombre', $param) and array_key_exists('medescripcion',$param)) {
            $obj = new Menu();
            $objMenu = null;
            if (isset($param['idpadre'])){
                $objMenu = new Menu();
                $objMenu->setIdMenu($param['idpadre']);
                $objMenu->cargar();
            }
            if(!isset($param['medeshabilitado'])) {
                $param['medeshabilitado'] = null;
            } else {
                $param['medeshabilitado'] = date("Y-m-d H:i:s");
            }
            $obj->setear($param['idmenu'], $param['menombre'], $param['medescripcion'], $objMenu, $param['medeshabilitado']); 
        }
        return $obj;
    }
    
    /**
     * Carga un objeto con clave.
     * @param array $param
     * @return Menu|null
     */
    private function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idmenu'])) {
            $obj = new Menu();
            $obj->setear($param['idmenu'], null, null, null, null);
        }
        return $obj;
    }
    
    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves.
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param) {
        return isset($param['idmenu']);
    }
    
    /**
     * Permite insertar un nuevo rol.
     * @param array $param
     * @return boolean
     */
    public function alta($param) {
        $resp = false;
        $param['idmenu'] = null;
        $param['medeshabilitado'] = null;
        $objMenu = $this->cargarObjeto($param);
        if ($objMenu != null && $objMenu->insertar()) {
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
            $objMenu = $this->cargarObjetoConClave($param);
            if ($objMenu != null && $objMenu->eliminar()) {
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
            $objMenu = $this->cargarObjeto($param);
            if ($objMenu != null && $objMenu->modificar()) {
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
            if (isset($param['idmenu']))
                $where .= " and idmenu =" . $param['idmenu'];
            if (isset($param['menombre']))
                $where .= " and menombre ='" . $param['menombre'] . "'";
            if (isset($param['medescripcion']))
                $where .= " and medescripcion ='" . $param['medescripcion'] . "'";
            if (isset($param['idpadre']))
                $where .= " and idpadre ='" . $param['idpadre'] . "'";
            if (isset($param['medeshabilitado']))
                $where .= " and medeshabilitado ='" . $param['medeshabilitado'] . "'";
        }
        $obj = new Menu();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }
}

?>