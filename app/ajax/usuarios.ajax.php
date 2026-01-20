<?php

// Requerimos el controlador y el modelo para que la clase Ajax pueda usarlos
require_once "../controllers/UsuariosController.php";
require_once "../models/UsuariosModel.php";

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
}

/**
 * OBJETOS DE RECEPCIÓN
 * Validamos si viene la variable del formulario para activar la clase
 */
if (isset($_POST["nuevoNombre"])) {

    $crear = new AjaxUsuarios();
    $crear->ajaxCrearUsuario();
}

if (isset($_POST["validarCorreo"])) {
    $valCorreo = new AjaxUsuarios();
    $valCorreo->validarCorreo = $_POST["validarCorreo"];
    $valCorreo->ajaxValidarCorreo();
}

if (isset($_POST["idEliminar"])) {
    $eliminar = new AjaxUsuarios();
    $eliminar->idEliminar = $_POST["idEliminar"];
    $eliminar->ajaxEliminarUsuario();
}
