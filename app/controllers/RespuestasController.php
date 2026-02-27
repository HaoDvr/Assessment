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
            $entrevistados = $datos["entrevistados_json"];
            $error = false;

            foreach ($datos["respuestas"] as $id_post => $valores) {

                $id_pregunta_real = explode("_copy_", $id_post)[0];

                $metadatos = [
                    "dominios" => $valores["dominios"] ?? [],
                    "servicios" => $valores["servicios"] ?? []
                ];
                $clasificacionJson = json_encode($metadatos, JSON_UNESCAPED_UNICODE);

                $omitida = $valores["omitir"] ?? "no";

                $datosSQL = array(
                    "token_respuesta"            => $token,
                    "id_usuario"                 => $id_usuario,
                    "id_pregunta"                => $id_pregunta_real,
                    "pregunta_txt"               => $valores["pregunta_txt"],
                    "id_respuesta_seleccionada"  => $valores["id_seleccionada"] ?? 0,
                    "respuesta_seleccionada_txt" => $valores["respuesta_txt"] ?? "Omitida/Reasignada",
                    "valor_respuesta"            => $valores["valor"] ?? 0,
                    "respuesta_libre_txt"        => $valores["libre"] ?? "",
                    "respuesta_detallada_txt"    => $valores["detallada"] ?? "",
                    "clasificacion_json"         => $clasificacionJson,
                    "nombre_usuario_txt"         => $nombre_usuario,
                    "entrevistados_json"         => $entrevistados,
                    "omitida"                    => $omitida,
                    "usuario_reasignado"         => $valores["usuario_reasignado"] ?? null,
                    "motivo_omision"             => $valores["motivo_omision"] ?? null,

                    // MAPEADO: El input HTML 'valor_sub_nivel' se guarda como 'sub_valor' para el Modelo
                    "sub_valor"                  => $valores["valor_sub_nivel"] ?? 0
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
