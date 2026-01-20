<?php
require_once "Conexion.php";

class ModeloUsuarios
{

    /*-- --------------------------
    * MOSTRAR USUARIOS / VALIDAR EXISTENTES
    -------------------------------*/
    static public function mdlMostrarUsuarios($tabla, $item, $valor)
    {
        if ($item != null) {

            // Si viene un item (ej. correo_usuario), buscamos solo ese registro
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(); // Retorna un solo array con el usuario encontrado

        } else {

            // Si no viene item, traemos todos los usuarios para la tabla
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt->execute();

            return $stmt->fetchAll(); // Retorna todos los registros

        }

        $stmt->close();
        $stmt = null;
    }

    /*-- --------------------------
    * INSERTAR USUARIOS
    -------------------------------*/
    static public function mdlIngresarUsuario($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre_usuario, apellido_paterno_usuario, apellido_materno_usuario, area_usuario, correo_usuario, area, perfil_usuario, password) VALUES (:nombre, :apellido_p, :apellido_m, :area_u, :correo, :area, :perfil, :password)");

        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":apellido_p", $datos["apellido_p"], PDO::PARAM_STR);
        $stmt->bindParam(":apellido_m", $datos["apellido_m"], PDO::PARAM_STR);
        $stmt->bindParam(":area_u", $datos["area"], PDO::PARAM_STR);
        $stmt->bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
        $stmt->bindParam(":area", $datos["area"], PDO::PARAM_STR);
        $stmt->bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }

        $stmt->close();
        $stmt = null;
    }

    /*-- --------------------------
    * BORRAR USUARIO
    -------------------------------*/
    static public function mdlEliminarUsuario($tabla, $id)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_usuarios = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }
}
