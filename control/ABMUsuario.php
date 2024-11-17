<?php

class ABMUsuario {

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
        if ($datos['accion'] == 'habilitar') {
            if ($this->habilitacion($datos)) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Carga un objeto Usuario desde un arreglo asociativo.
     * @param array $param
     * @return Usuario|null
     */
    private function cargarObjeto($param) {
        $obj = null;
        if (array_key_exists('idusuario', $param) && array_key_exists('usnombre', $param) && 
            array_key_exists('uspass', $param) && array_key_exists('usmail', $param) && 
            array_key_exists('usdeshabilitado', $param)) {
            $obj = new Usuario();
            $obj->setear($param['idusuario'], $param['usnombre'], $param['uspass'], $param['usmail'], $param['usdeshabilitado']);
        }
        return $obj;
    }
    
    /**
     * Carga un objeto Usuario solo con la clave primaria.
     * @param array $param
     * @return Usuario|null
     */
    private function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idusuario'])) {
            $obj = new Usuario();
            $obj->setear($param['idusuario'], null, null, null, null);
        }
        return $obj;
    }
    
    /**
     * Verifica si los campos clave están definidos en el arreglo.
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param) {
        return isset($param['idusuario']);
    }
    
    /**
     * Permite insertar un nuevo usuario.
     * @param array $param
     * @return boolean
     */
    public function alta($param) {
        $resp = false;
        $param['idusuario'] = null;
        $objUsuario = $this->cargarObjeto($param);
        if ($objUsuario != null && $objUsuario->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Permite eliminar un usuario.
     * @param array $param
     * @return boolean
     */
    public function baja($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objUsuario = $this->cargarObjetoConClave($param);
            if ($objUsuario != null && $objUsuario->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }
    
    /**
     * Permite modificar un usuario.
     * @param array $param
     * @return boolean
     */
    public function modificacion($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objUsuario = $this->cargarObjeto($param);
            if ($objUsuario != null && $objUsuario->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Permite buscar usuarios según parámetros.
     * @param array $param
     * @return array
     */
    /*
    public function buscar($param) {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idusuario']))
                $where .= " and idusuario =" . $param['idusuario'];
            if (isset($param['usnombre']))
                $where .= " and usnombre ='" . $param['usnombre'] . "'";
            if (isset($param['usmail']))
                $where .= " and usmail ='" . $param['usmail'] . "'";
            if (isset($param['usdeshabilitado']))
                $where .= " and usdeshabilitado =" . $param['usdeshabilitado'];
        }
        $obj = new Usuario();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }*/

    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idusuario']))
                $where.=" and idusuario =".$param['idusuario'];
            if  (isset($param['usnombre']))
                 $where.=" and usnombre ='".$param['usnombre']."'";
            if  (isset($param['usmail']))
                 $where.=" and usmail ='".$param['usmail']."'";
            if  (isset($param['uspass']))
                 $where.=" and uspass ='".$param['uspass']."'";
            if  (isset($param['usdeshabilitado']))
                 $where.=" and usdeshabilitado ='".$param['usdeshabilitado']."'";
        }
        $obj = new Usuario();
        $arreglo = $obj->listar($where);
        //echo "Van ".count($arreglo);
        return $arreglo;
    }

    public function habilitacion($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objUsuario = $this->cargarObjeto($param);
            if ($objUsuario != null && $objUsuario->habilitar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    function UsuarioValido($datos) {

        $objUsuario = new Usuario();
        $usuarios = $objUsuario->listar();
        $found = false;
        foreach ($usuarios as $usuario) {
            if ($usuario->getUsMail() == $datos['usmail'] && $usuario->getUsPass() == $datos['uspass']) {
                // Usuario válido
                $resp = json_encode(['error' => false, 'nombre' => $usuario->getUsMail()]);
                $found = true;
                break;
            }
        }
        if (!$found) {
            // Usuario no válido
            $resp = json_encode(['error' => true, 'mensaje' => "Datos incorrectos"]);
        }
        return $resp;
    }
}

?>