<?php

require_once __DIR__ . "/Conexion.php";

class RespuestasModelo
{
    static public function mdlGuardarRespuesta($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (
            token_respuesta, id_usuario, id_pregunta, pregunta_txt,
            id_respuesta_seleccionada, respuesta_seleccionada_txt,
            valor_respuesta, respuesta_libre_txt, respuesta_detallada_txt,
            clasificacion_json, nombre_usuario_txt, entrevistados_json,
            omitida, usuario_reasignado, motivo_omision
        ) VALUES (
            :token, :id_u, :id_p, :p_txt, :id_r, :r_txt, :valor, :libre,
            :detallada, :clasif, :nom_u, :entre_js, :omit, :u_reasig, :motivo
        )");

        $stmt->bindParam(":token",      $datos["token_respuesta"], PDO::PARAM_STR);
        $stmt->bindParam(":id_u",       $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":id_p",       $datos["id_pregunta"], PDO::PARAM_INT);
        $stmt->bindParam(":p_txt",      $datos["pregunta_txt"], PDO::PARAM_STR);
        $stmt->bindParam(":id_r",       $datos["id_respuesta_seleccionada"], PDO::PARAM_INT);
        $stmt->bindParam(":r_txt",      $datos["respuesta_seleccionada_txt"], PDO::PARAM_STR);
        $stmt->bindParam(":valor",      $datos["valor_respuesta"], PDO::PARAM_INT);
        $stmt->bindParam(":libre",      $datos["respuesta_libre_txt"], PDO::PARAM_STR);
        $stmt->bindParam(":detallada",  $datos["respuesta_detallada_txt"], PDO::PARAM_STR);
        $stmt->bindParam(":clasif",     $datos["clasificacion_json"], PDO::PARAM_STR);
        $stmt->bindParam(":nom_u",      $datos["nombre_usuario_txt"], PDO::PARAM_STR);
        $stmt->bindParam(":entre_js",   $datos["entrevistados_json"], PDO::PARAM_STR); // Fase 1
        $stmt->bindParam(":omit",       $datos["omitida"], PDO::PARAM_STR); // Fase 2
        $stmt->bindParam(":u_reasig",   $datos["usuario_reasignado"], PDO::PARAM_STR); // Fase 2
        $stmt->bindParam(":motivo",     $datos["motivo_omision"], PDO::PARAM_STR); // Fase 2

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }
}
