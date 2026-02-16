<?php

class RespuestasControlador
{
    static public function ctrGuardarEncuesta($datos)
    {
        if (isset($datos["respuestas"])) {
            $tabla = "respuestas_usuarios";
            $id_usuario = $datos["id_usuario"];
            $nombre_usuario = $datos["nombre_usuario_txt"];
            $token = $datos["token_respuesta"];
            $error = false;

            foreach ($datos["respuestas"] as $id_pregunta => $valores) {

                // --- NUEVA LÓGICA PARA CLASIFICACIÓN JSON ---
                // Capturamos los arrays del Select2. Si no vienen, inicializamos vacío.
                $metadatos = [
                    "dominios" => isset($valores["dominios"]) ? $valores["dominios"] : [],
                    "servicios" => isset($valores["servicios"]) ? $valores["servicios"] : []
                ];

                // Convertimos el array a string JSON para MySQL
                $clasificacionJson = json_encode($metadatos, JSON_UNESCAPED_UNICODE);
                // --------------------------------------------

                $datosSQL = array(
                    "token_respuesta"           => $token,
                    "id_usuario"                => $id_usuario,
                    "id_pregunta"               => $id_pregunta,
                    "pregunta_txt"              => $valores["pregunta_txt"],
                    "id_respuesta_seleccionada" => $valores["id_seleccionada"],
                    "respuesta_seleccionada_txt" => $valores["respuesta_txt"],
                    "valor_respuesta"           => $valores["valor"],
                    "respuesta_libre_txt"       => $valores["libre"],
                    "respuesta_detallada_txt"   => $valores["detallada"],
                    "clasificacion_json"        => $clasificacionJson, // Agregamos la nueva columna
                    "nombre_usuario_txt"        => $nombre_usuario
                );

                $respuesta = RespuestasModelo::mdlGuardarRespuesta($tabla, $datosSQL);

                if ($respuesta != "ok") {
                    $error = true;
                    break;
                }
            }
            return (!$error) ? "ok" : "error";
        }
    }
}
