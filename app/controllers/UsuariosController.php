<?php

class UsuariosController
{
    /*-- --------------------------
    * Mostrar datos en la pantalla
    -------------------------------*/
    public function index()
    {
        // Aquí definimos los parámetros para el diseño dashboard
        $config = [
            "titulo" => "Usuarios",
            "breadcrumbActivo" => "Usuarios",
            "load_datatables" => true // Le avisamos al footer que cargue los scripts
        ];

        return $config;
    }

    /*-- --------------------------
    * MOSTRAR LOS USUARIOS
    * $item: nombre de la columna (ej: correo_usuario)
    * $valor: el valor a buscar (ej: vramirez@nttdata.com)
    -------------------------------*/
    static public function ctrMostrarUsuarios($item = null, $valor = null)
    {
        $tabla = "usuarios";

        // Pasamos los parámetros al modelo para que decida si busca uno o todos
        $respuesta = ModeloUsuarios::mdlMostrarUsuarios($tabla, $item, $valor);

        return $respuesta;
    }

    /*-- --------------------------
    * AGREGAR USUARIO
    -------------------------------*/
    static public function ctrCrearUsuario()
    {
        if (isset($_POST["nuevoNombre"])) {

            // 1. Validamos solo los campos de texto
            $soloTexto = $_POST["nuevoNombre"] . $_POST["nuevoApellidoP"] . $_POST["nuevoApellidoM"] . $_POST["nuevaArea"];

            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $soloTexto)) {

                // 2. Validación específica para el Correo (Estándar Senior)
                if (!filter_var($_POST["nuevoCorreo"], FILTER_VALIDATE_EMAIL)) {
                    return "error_correo";
                }

                $tabla = "usuarios";
                $encrypt = password_hash($_POST["nuevaContrasena"], PASSWORD_DEFAULT);

                $datos = array(
                    "nombre"     => $_POST["nuevoNombre"],
                    "apellido_p" => $_POST["nuevoApellidoP"],
                    "apellido_m" => $_POST["nuevoApellidoM"],
                    "area"       => $_POST["nuevaArea"],
                    "correo"     => $_POST["nuevoCorreo"],
                    "perfil"     => $_POST["nuevoPerfil"],
                    "password"   => $encrypt
                );

                $respuesta = ModeloUsuarios::mdlIngresarUsuario($tabla, $datos);
                return $respuesta;
            } else {
                return "error_formato";
            }
        }
    }

    /*-- --------------------------
    * BORRAR USUARIO
    -------------------------------*/
    static public function ctrEliminarUsuario($id)
    {
        $tabla = "usuarios";
        $respuesta = ModeloUsuarios::mdlEliminarUsuario($tabla, $id);
        return $respuesta;
    }
}
