<?php

require_once "conexion.php";

class RespuestasModelo
{
    static public function mdlGuardarRespuesta($tabla, $datos)
    {
        // Agregamos 'clasificacion_json' tanto en las columnas como en los valores (:clasif)
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (
            token_respuesta,
            id_usuario,
            id_pregunta,
            pregunta_txt,
            id_respuesta_seleccionada,
            respuesta_seleccionada_txt,
            valor_respuesta,
            respuesta_libre_txt,
            respuesta_detallada_txt,
            clasificacion_json,
            nombre_usuario_txt
        ) VALUES (
            :token,
            :id_u,
            :id_p,
            :p_txt,
            :id_r,
            :r_txt,
            :valor,
            :libre,
            :detallada,
            :clasif,
            :nom_u
        )");

        // Vinculación de parámetros
        $stmt->bindParam(":token",     $datos["token_respuesta"], PDO::PARAM_STR);
        $stmt->bindParam(":id_u",      $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":id_p",      $datos["id_pregunta"], PDO::PARAM_INT);
        $stmt->bindParam(":p_txt",     $datos["pregunta_txt"], PDO::PARAM_STR);
        $stmt->bindParam(":id_r",      $datos["id_respuesta_seleccionada"], PDO::PARAM_INT);
        $stmt->bindParam(":r_txt",     $datos["respuesta_seleccionada_txt"], PDO::PARAM_STR);
        $stmt->bindParam(":valor",     $datos["valor_respuesta"], PDO::PARAM_INT);
        $stmt->bindParam(":libre",     $datos["respuesta_libre_txt"], PDO::PARAM_STR);
        $stmt->bindParam(":detallada", $datos["respuesta_detallada_txt"], PDO::PARAM_STR);
        // El JSON se pasa como STRING (PARAM_STR) porque json_encode ya hizo la conversión
        $stmt->bindParam(":clasif",    $datos["clasificacion_json"], PDO::PARAM_STR);
        $stmt->bindParam(":nom_u",     $datos["nombre_usuario_txt"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            // Debug para desarrollo: muestra el error si algo falla en la ejecución
            // return $stmt->errorInfo();
            return "error";
        }
    }
}
