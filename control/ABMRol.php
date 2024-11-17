<?php

class ABMRol {

    public function abm($datos) {
        $resp = false;
        if ($datos['accion'] == 'editar') {
            if ($this->modificacion($datos)) {
                $resp = true;
            }
        }
        if ($datos['accion'] == 'borrar') {
            if ($this->baja($datos)) {
                $resp = true;
            }
        }
        if ($datos['accion'] == 'nuevo') {
            if ($this->alta($datos)) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Carga un objeto desde un arreglo asociativo.
     * @param array $param
     * @return Rol|null
     */
    private function cargarObjeto($param) {
        $obj = null;
        if (array_key_exists('idrol', $param) && array_key_exists('rodescripcion', $param)) {
            $obj = new Rol();
            $obj->setear($param['idrol'], $param['rodescripcion']);
        }
        return $obj;
    }
    
    /**
     * Carga un objeto con clave.
     * @param array $param
     * @return Rol|null
     */
    private function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idrol'])) {
            $obj = new Rol();
            $obj->setear($param['idrol'], null);
        }
        return $obj;
    }
    
    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves.
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param) {
        return isset($param['idrol']);
    }
    
    /**
     * Permite insertar un nuevo rol.
     * @param array $param
     * @return boolean
     */
    public function alta($param) {
        $resp = false;
        $param['idrol'] = null;
        $objRol = $this->cargarObjeto($param);
        if ($objRol != null && $objRol->insertar()) {
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
            $objRol = $this->cargarObjetoConClave($param);
            if ($objRol != null && $objRol->eliminar()) {
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
            $objRol = $this->cargarObjeto($param);
            if ($objRol != null && $objRol->modificar()) {
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
            if  (isset($param['idrol']))
                $where .= " and idrol =" . $param['idrol'];
            if  (isset($param['rodescripcion']))
                 $where .= " and rodescripcion ='" . $param['rodescripcion'] . "'";
        }
        $obj = new Rol();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }

}

?>