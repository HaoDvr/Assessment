<?php

// Requerimos el controlador y el modelo para que la clase Ajax pueda usarlos
require_once __DIR__ . "/../controllers/UsuariosController.php";
require_once __DIR__ . "/../models/UsuariosModel.php";

class AjaxUsuarios
{
    /**
     * MÉTODO PARA VALIDAR CORREO DEL USUARIO EXISTENTE
     * Este método llama al controlador y devuelve la respuesta al JS
     */
    public $validarCorreo;

    public function ajaxValidarCorreo()
    {
        $item = "correo_usuario";
        $valor = $this->validarCorreo;

        // Pedimos al controlador que busque si existe
        $respuesta = UsuariosController::ctrMostrarUsuarios($item, $valor);

        echo json_encode($respuesta);
    }

    /**
     * MÉTODO PARA CREAR USUARIO
     * Este método llama al controlador y devuelve la respuesta al JS
     */
    public function ajaxCrearUsuario()
    {

        $respuesta = UsuariosController::ctrCrearUsuario();

        // Convertimos la respuesta (ej: "ok") a formato JSON para el Fetch de JS
        echo json_encode($respuesta);
    }

    /**
     * MÉTODO PARA BORRAR USUARIO
     * Este método llama al controlador y devuelve la respuesta al JS
     */
    public $idEliminar;

    public function ajaxEliminarUsuario()
    {
        $respuesta = UsuariosController::ctrEliminarUsuario($this->idEliminar);
        echo json_encode($respuesta);
    }

    /*-- --------------------------
    * EDITAR USUARIO
    -------------------------------*/
    public $idUsuario;

    public function ajaxEditarUsuario()
    {
        if (ob_get_length()) ob_clean(); // Limpia el HTML que se coló

        $item = "id_usuarios";
        $valor = $this->idUsuario;
        $respuesta = UsuariosController::ctrMostrarUsuarios($item, $valor);

        echo json_encode($respuesta);
        exit(); // Detiene la carga del Template o Header
    }
}

/*=============================================
OBJETO PARA REGISTRAR USUARIO
=============================================*/
if (isset($_POST["nuevoNombre"])) {

    $crear = new AjaxUsuarios();
    $crear->ajaxCrearUsuario();
}

/*=============================================
OBJETO PARA VALIDAR CORREO
=============================================*/
if (isset($_POST["validarCorreo"])) {
    $valCorreo = new AjaxUsuarios();
    $valCorreo->validarCorreo = $_POST["validarCorreo"];
    $valCorreo->ajaxValidarCorreo();
}

/*=============================================
OBJETO PARA ELIMINAR USUARIO
=============================================*/
if (isset($_POST["idEliminar"])) {
    $eliminar = new AjaxUsuarios();
    $eliminar->idEliminar = $_POST["idEliminar"];
    $eliminar->ajaxEliminarUsuario();
}

/*=============================================
OBJETO PARA EDITAR USUARIO
=============================================*/
if (isset($_POST["idUsuario"])) {
    $editar = new AjaxUsuarios();
    $editar->idUsuario = $_POST["idUsuario"];
    $editar->ajaxEditarUsuario();
}
