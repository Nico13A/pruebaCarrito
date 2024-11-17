<?php

class ABMMenuRol {
    /**
     * Carga un objeto desde un arreglo asociativo.
     * @param array $param
     * @return object
     */
    public function cargarObjeto($param) {
        $objMenuRol = null;
        $objMenu = null;
        $objRol = null;

        if (array_key_exists('idmenu', $param) && array_key_exists('idrol', $param)) {
            $objMenu = new Menu();
            $objMenu->setIdMenu($param['idmenu']);
            $objMenu->cargar();

            $objRol = new Rol();
            $objRol->setIdRol($param['idrol']);
            $objRol->cargar();

            $objMenuRol = new MenuRol();
            $objMenuRol->setear($objMenu, $objRol);
        }
        return $objMenuRol;
    }

    /**
     * Carga un objeto con clave.
     * @param array $param
     * @return MenuRol|null
     */
    private function cargarObjetoConClave($param) {
        $objMenuRol = null;
        $objRol = null;
        $objMenu = null;

        if (isset($param['idmenu']) && isset($param['idrol']) ){
            if ($param['idmenu'] != null) {
                $objMenu = new Menu();
                $objMenu->setear($param['idmenu'], null, null, null, null);
            }
            if ($param['idrol'] != null) {
                $objRol = new Rol();
                $objRol->setear($param['idrol'], null);
            }
            $objMenuRol = new MenuRol();
            $objMenuRol-> setear($objMenu, $objRol);
        }
        return $objMenuRol;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves.
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param) {
        return isset($param['idmenu']) && isset($param['idrol']);
    }

    /**
     * Permite insertar un nuevo objeto.
     * @param array $param
     * @return boolean
     */
    public function alta($param) {
        $resp = false;
        $objMenuRol = $this->cargarObjeto($param);
        if ($objMenuRol != null && $objMenuRol->insertar()) {
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
            $objMenuRol = $this->cargarObjetoConClave($param);
            if ($objMenuRol != null && $objMenuRol->eliminar()) {
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
            $objMenuRol = $this->cargarObjeto($param);
            if ($objMenuRol != null && $objMenuRol->modificar()) {
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
            if (isset($param['idrol']))
                $where .= " and idrol =" . $param['idrol'];
        }
        $objMenuRol = new MenuRol();
        $arreglo = $objMenuRol->listar($where);
        return $arreglo;
    }


    /**
     * Obtiene los menús asociados a un rol específico.
     * @param int $idRol
     * @return array
     */
    public function obtenerMenuesPorRol($idRol) {
        $where = " idrol = " . $idRol;
        $objMenuRol = new MenuRol();
        $arregloMenuRol = $objMenuRol->listar($where);
        $menues = [];
        foreach ($arregloMenuRol as $menuRol) {
            $menu = $menuRol->getObjMenu(); 
            $menu->cargar(); 
            $menues[] = $menu;
        }
        return $menues;
    }

}

?>