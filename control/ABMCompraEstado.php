<?php

class ABMCompraEstado {
    /**
     * Carga un objeto desde un arreglo asociativo.
     * @param array $param
     * @return object
     */
    private function cargarObjeto($param) {
        $obj = null;
        if (array_key_exists('idcompraestado', $param) && array_key_exists('idcompra', $param) && array_key_exists('idcompraestadotipo', $param) && array_key_exists('cefechaini', $param) && array_key_exists('cefechafin', $param)) {
            $obj = new CompraEstado();
            $objCompra = null;
            $objCompraEstadoTipo = null;
            if ($param['idcompra'] != null) {
                $objCompra = new Compra();
                $objCompra->setIdCompra($param['idcompra']);
                $objCompra->cargar();
            }
            if ($param['idcompraestadotipo'] != null) {
                $objCompraEstadoTipo = new CompraEstadoTipo();
                $objCompraEstadoTipo->setIdCompraEstadoTipo($param['idcompraestadotipo']);
                $objCompraEstadoTipo->cargar();
            }
            $obj->setear($param['idcompraestado'], $objCompra, $objCompraEstadoTipo, $param['cefechaini'], $param['cefechafin']);
        }
        return $obj;
    }

    /**
     * Carga un objeto con clave.
     * @param array $param
     * @return CompraEstado|null
     */
    private function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idcompraestado'])) {
            $obj = new CompraEstado();
            $obj->setear($param['idcompraestado'], null, null, null, null);
        }
        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves.
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param) {
        return isset($param['idcompraestado']);
    }

    /**
     * Permite insertar un nuevo objeto.
     * @param array $param
     * @return boolean
     */
    public function alta($param) {
        $resp = false;
        $param['idcompraestado'] = null;
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
            if ($obj != null && $obj->eliminar()) {
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
            if  (isset($param['idcompraestado']))
                $where.=" and idcompraestado ='".$param['idcompraestado']."'";
            if  (isset($param['idcompra']))
                $where.=" and idcompra ='".$param['idcompra']."'";
            if  (isset($param['idcompraestadotipo']))
                $where.=" and idcompraestadotipo ='".$param['idcompraestadotipo']."'";
            if  (isset($param['cefechaini']))
                $where.=" and cefechaini ='".$param['cefechaini']."'";
            if  (isset($param['cefechafin']))
                $where.=" and cefechafin ='".$param['cefechafin']."'";
        }
        $obj = new CompraEstado();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }

    /**
     * Busca una compra en estado "carrito".
     * @param array $comprasUsuario Array de compras del usuario.
     * @return CompraEstado|null 
     */
    /*
    public function buscarCompraEnEstadoCarrito($comprasUsuario) {
        $estadoCarrito = 1; // Suponiendo que el estado "carrito" tiene idcompraestadotipo = 1
        $compraEnCarrito = null;

        foreach ($comprasUsuario as $compra) {
            $param = [
                'idcompra' => $compra->getIdCompra(),
                'idcompraestadotipo' => $estadoCarrito,
            ];
            $resultados = $this->buscar($param);

            if (!empty($resultados)) {
                $compraEnCarrito = $resultados[0]; // Tomamos la primera coincidencia.
                break;
            }
        }

        return $compraEnCarrito;
    }*/
    public function buscarCompraEnEstadoCarrito($comprasUsuario) {
        $compraEnCarrito = null;
        $i = 0;

        // Busca en el array de compras si hay alguna con el estado "carrito".
        while (($compraEnCarrito == null) && ($i < count($comprasUsuario))) {
            $idCompra["idcompra"] = $comprasUsuario[$i]->getIdCompra();
            $arrayCompraEstado = $this->buscar($idCompra);

            // Verifica si el estado de la compra es "carrito".
            if ($arrayCompraEstado[0]->getObjCompraEstadoTipo()->getCetDescripcion() == "iniciada") {
                $compraEnCarrito = $arrayCompraEstado[0];
            } else {
                $i++;
            }
        }

        return $compraEnCarrito;
    }






    /*
    public function buscarCompraCarrito($arrayCompra) {
        $objCompraEstadoCarrito = null;
        $i = 0;
        // Iterar por cada compra para encontrar la que está en estado "carrito"
        while (($objCompraEstadoCarrito == null) && ($i < count($arrayCompra))) {
            // Obtener el ID de la compra actual
            $idCompra["idcompra"] = $arrayCompra[$i]->getIdCompra();
            // Buscar los estados asociados a esta compra
            $arrayCompraEstado = $this->buscar($idCompra);
            // Validar si alguno de los estados es "carrito"
            if (!empty($arrayCompraEstado) && $arrayCompraEstado[0]->getCompraEstadoTipo()->getCetDescripcion() == "carrito") {
                $objCompraEstadoCarrito = $arrayCompraEstado[0];
            }
            $i++;
        }
        // Devolver la compra en estado "carrito" o null si no se encuentra
        return $objCompraEstadoCarrito;
    }
    */

/*
    public function obtenerOCrearCarrito($idUsuario) {
        $compraCarrito = null;
    
        // Buscar compras existentes del usuario
        $abmCompra = new ABMCompra();
        $compras = $abmCompra->buscarComprasDeUsuario($idUsuario);
    
        // Buscar si alguna compra tiene estado "carrito"
        foreach ($compras as $compra) {
            $compraEstados = $this->buscar(['idcompra' => $compra->getIdCompra()]);
            foreach ($compraEstados as $compraEstado) {
                if ($compraEstado->getObjCompraEstadoTipo()->getIdCompraEstadoTipo() == 1) { // Estado "carrito"
                    $compraCarrito = $compra;
                    break 2; // Rompemos ambos loops si encontramos el carrito
                }
            }
        }
    
        // Si no existe, crear una nueva compra con estado "carrito"
        if (!$compraCarrito) {
            // Crear una nueva compra
            $nuevaCompra = [
                'idusuario' => $idUsuario,
            ];
            
            if ($abmCompra->alta($nuevaCompra)) {
                $ultimaCompra = $abmCompra->obtenerUltimaCompra($idUsuario);
    
                if ($ultimaCompra) {
                    // Crear el estado "carrito" para la nueva compra
                    $paramEstado = [
                        "idcompra" => $ultimaCompra->getIdCompra(),
                        "idcompraestadotipo" => 1, // Estado "carrito"
                        "cefechaini" => (new DateTime())->format('Y-m-d H:i:s'),
                        "cefechafin" => null
                    ];
    
                    // Usamos ABMCompraEstado para agregar el estado "carrito"
                    $this->alta($paramEstado);
                    $compraCarrito = $ultimaCompra;
                }
            }
        }
    
        return $compraCarrito;
    }
    */
    
    


}

?>