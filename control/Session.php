<?php

class Session {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Actualiza las variables de sesión con los valores ingresados.
     */
    /*
    public function iniciar($nombreUsuario, $psw) {
        $resp = false;
        $obj = new ABMUsuario();
        $param['usnombre'] = $nombreUsuario;
        $param['uspass'] = $psw;
        $param['usdeshabilitado'] = null;

        $resultado = $obj->buscar($param);
        if(count($resultado) > 0){
            $usuario = $resultado[0];
            $_SESSION['idusuario'] = $usuario->getIdUsuario();
            $resp = true;
        } else {
            $this->cerrar();
        }
        return $resp;
    }
    */
    public function iniciar($nombreUsuario, $psw) {
        $resp = false;
        $obj = new ABMUsuario();
        $param['usnombre'] = $nombreUsuario;
        $param['usdeshabilitado']=null; // Aseguramos que el usuario no esté deshabilitado

        // Buscar el usuario por nombre
        $resultado = $obj->buscar($param);
        if (count($resultado) > 0) {
            $usuario = $resultado[0];
            // Verificar la contraseña
            if ($usuario->verificarClave($psw)) {
                $_SESSION['idusuario'] = $usuario->getIdUsuario();
                $resp = true;
            } else {
                $this->cerrar();
            }
        } else {
            $this->cerrar();
        }
        return $resp;
    }

    /**
     * Valida si la sesión tiene un usuario y contraseña válidos.
     * @return boolean
     */
    public function validar() {
        return $this->activa() && isset($_SESSION['idusuario']);
    }

    public function activa() {
        return session_status() == PHP_SESSION_ACTIVE;
    }

    /**
     * Obtiene el usuario logeado en la sesión.
     * @return Usuario|null
     */
    public function getUsuario() {
        $usuario = null;
        if ($this->validar()) {
            $obj = new ABMUsuario();
            $param['idusuario'] = $_SESSION['idusuario'];
            $resultado = $obj->buscar($param);
            if (count($resultado) > 0) {
                $usuario = $resultado[0];
            }
        }
        return $usuario;
    }

    public function getRol() {
        $arrayRolesUsuario = null;
        $usuario = $this->getUsuario();
        if($usuario != null) {
            $objUsuarioRol = new ABMUsuarioRol();
            $param['idusuario'] = $usuario->getIdUsuario();
            $arrayRolesUsuario = $objUsuarioRol->buscar($param);
        }
        return $arrayRolesUsuario;
    }
    
    /**
     * Cierra la sesión actual.
     */
    public function cerrar() {
        $resp = true;
        session_unset();
        session_destroy();
        return $resp;
    }

}

?>