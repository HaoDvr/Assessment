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
            $entrevistados = $datos["entrevistados_json"]; // Fase 1
            $error = false;

            foreach ($datos["respuestas"] as $id_post => $valores) {

                // FASE 3: Limpiamos el ID en caso de ser un clon (ej: 5_copy_1740...)
                $id_pregunta_real = explode("_copy_", $id_post)[0];

                // Lógica de clasificación Select2 (Se mantiene)
                $metadatos = [
                    "dominios" => $valores["dominios"] ?? [],
                    "servicios" => $valores["servicios"] ?? []
                ];
                $clasificacionJson = json_encode($metadatos, JSON_UNESCAPED_UNICODE);

                // FASE 2: Lógica de Omisión y Reasignación
                $omitida = $valores["omitir"] ?? "no";

                $datosSQL = array(
                    "token_respuesta"           => $token,
                    "id_usuario"                => $id_usuario,
                    "id_pregunta"               => $id_pregunta_real, // ID Limpio
                    "pregunta_txt"              => $valores["pregunta_txt"],
                    "id_respuesta_seleccionada" => $valores["id_seleccionada"] ?? 0,
                    "respuesta_seleccionada_txt" => $valores["respuesta_txt"] ?? "Omitida/Reasignada",
                    "valor_respuesta"           => $valores["valor"] ?? 0,
                    "respuesta_libre_txt"       => $valores["libre"] ?? "",
                    "respuesta_detallada_txt"   => $valores["detallada"] ?? "",
                    "clasificacion_json"        => $clasificacionJson,
                    "nombre_usuario_txt"        => $nombre_usuario,
                    "entrevistados_json"        => $entrevistados, // Fase 1
                    "omitida"                   => $omitida, // Fase 2
                    "usuario_reasignado"        => $valores["usuario_reasignado"] ?? null, // Fase 2
                    "motivo_omision"            => $valores["motivo_omision"] ?? null // Fase 2
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
