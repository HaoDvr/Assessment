<?php
require_once __DIR__ . "/Conexion.php";

class ModeloUsuarios
{

    /*-- --------------------------
    * MOSTRAR USUARIOS / VALIDAR EXISTENTES
    -------------------------------*/
    static public function mdlMostrarUsuarios($tabla, $item, $valor)
    {
        if ($item != null) {

            // Si viene un item (ej. email_usuario), buscamos solo ese registro
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();

            $resultado = $stmt->fetch(); // Guardamos en variable para poder cerrar la conexión antes de retornar

        } else {

            // Si no viene item, traemos todos los usuarios para la tabla
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt->execute();

            $resultado = $stmt->fetchAll(); // Guardamos todos los registros en variable

        }

        // Cerramos la conexión y limpiamos el objeto
        $stmt->closeCursor(); // En PDO se recomienda closeCursor() para liberar la conexión al servidor
        $stmt = null;

        return $resultado; // Ahora sí, retornamos los datos ya con la conexión cerrada
    }

    /*-- --------------------------
    * INSERTAR USUARIOS
    -------------------------------*/
    static public function mdlIngresarUsuario($tabla, $datos)
    {
        // Eliminamos las repeticiones de 'area' y ':area_u'
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre_usuario, apellido_paterno_usuario, apellido_materno_usuario, area, correo_usuario, perfil_usuario, password) VALUES (:nombre, :apellido_p, :apellido_m, :area, :correo, :perfil, :password)");

        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":apellido_p", $datos["apellido_p"], PDO::PARAM_STR);
        $stmt->bindParam(":apellido_m", $datos["apellido_m"], PDO::PARAM_STR);
        $stmt->bindParam(":area", $datos["area"], PDO::PARAM_STR);
        $stmt->bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
        $stmt->bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            // Esto te ayudará a ver errores de SQL en la consola si vuelve a fallar
            return "error: " . $stmt->errorInfo()[2];
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

    /*-- --------------------------
    * EDITAR USUARIO
    -------------------------------*/
    static public function mdlEditarUsuario($tabla, $datos)
    {

        // Preparamos la sentencia SQL con todos los campos de tu tabla
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla
        SET nombre_usuario = :nombre,
            apellido_paterno_usuario = :apellidoP,
            apellido_materno_usuario = :apellidoM,
            area = :area,
            correo_usuario = :correo,
            perfil_usuario = :perfil,
            password = :password
        WHERE id_usuarios = :id");

        // Vinculamos cada parámetro para evitar Inyección SQL
        $stmt->bindParam(":nombre", $datos["nombre_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":apellidoP", $datos["apellido_paterno_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":apellidoM", $datos["apellido_materno_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":area", $datos["area_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":correo", $datos["correo_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":perfil", $datos["perfil_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
        $stmt->bindParam(":id", $datos["id_usuarios"], PDO::PARAM_INT);

        // Ejecutamos y cerramos la conexión
        if ($stmt->execute()) {
            return "ok";
        } else {
            // En caso de error, puedes imprimir el error para debuggear
            // return $stmt->errorInfo();
            return "error";
        }

        $stmt->closeCursor();
        $stmt = null;
    }
}
