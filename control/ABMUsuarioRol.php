<?php

class ABMUsuarioRol {

    /**
     * Realiza operaciones CRUD según la acción especificada en el array de datos.
     * @param array $datos
     * @return boolean
     */
    public function abm($datos) {
        $resp = false;
        if ($datos['accion'] == 'nuevo') {
            $resp = $this->alta($datos);
        } elseif ($datos['accion'] == 'editar') {
            $resp = $this->modificacion($datos);
        } elseif ($datos['accion'] == 'borrar') {
            $resp = $this->baja($datos);
        }
        return $resp;
    }

    /**
     * Carga un objeto UsuarioRol desde un arreglo asociativo.
     * @param array $datos
     * @return UsuarioRol|null
     */
    private function cargarObjeto($param) {
        $objUsuarioRol = null;
        $objRol = null;
        $objUsuario = null;

        if (array_key_exists('idusuario', $param) && array_key_exists('idrol',$param)) {
            $objUsuario = new Usuario();
            $objUsuario->setIdUsuario($param['idusuario']);
            $objUsuario->cargar();

            $objRol = new Rol();
            $objRol->setIdRol($param['idrol']);
            $objRol->cargar();

            $objUsuarioRol = new UsuarioRol();
            $objUsuarioRol->setear($objUsuario, $objRol);
        }
        return $objUsuarioRol;
    }

    /**
     * Carga un objeto UsuarioRol con clave.
     * @param array $param
     * @return UsuarioRol|null
     */
    private function cargarObjetoConClave($param) {
        $objUsuarioRol = null;
        if (isset($param['idusuario']) && isset($param['idrol'])) {
            $usuario = new Usuario();
            $usuario->setIdUsuario($param['idusuario']);
            $usuario->cargar();

            $rol = new Rol();
            $rol->setIdRol($param['idrol']);
            $rol->cargar();

            $objUsuarioRol = new UsuarioRol();
            $objUsuarioRol->setear($usuario, $rol);
        }
        return $objUsuarioRol;
    }

    /**
     * Verifica que los campos clave estén definidos en el arreglo.
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param) {
        return isset($param['idusuario']) && isset($param['idrol']);
    }

    /**
     * Permite crear una nueva relación entre Usuario y Rol.
     * @param array $datos
     * @return boolean
     */
    public function alta($datos) {
        $resp = false;
        $objUsuarioRol = $this->cargarObjeto($datos);
        if ($objUsuarioRol != null && $objUsuarioRol->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Permite eliminar un rol asignado a un usuario.
     * @param array $param
     * @return boolean
     */
    public function baja($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objUsuarioRol = $this->cargarObjetoConClave($param);
            if ($objUsuarioRol != null && $objUsuarioRol->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Permite modificar la relación entre usuario y rol.
     * @param array $param
     * @return boolean
     */
    public function modificacion($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objUsuarioRol = $this->cargarObjeto($param);
            if ($objUsuarioRol != null && $objUsuarioRol->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Permite buscar una relación de usuario y rol.
     * @param array $param
     * @return array
     */
    public function buscar($param) {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idusuario']))
                $where .= " and idusuario =" . $param['idusuario'];
            if (isset($param['idrol']))
                $where .= " and idrol =" . $param['idrol'];
        }
        $obj = new UsuarioRol();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }
    
}

?>
