<?php
require_once "conexion.php";

class ModeloMostrarPreguntas
{
    static public function mdlMostrarPreguntasFiltradas($tabla, $entrevistado, $areas)
    {
        // 1. Quitamos p.usuario_asignado del WHERE porque las preguntas son globales.
        // Mantenemos el JOIN para saber si ESTE entrevistado ya respondió.
        $sql = "SELECT p.* FROM $tabla p
            LEFT JOIN respuestas_usuarios r ON (p.preguntas_final_id = r.id_pregunta AND r.nombre_usuario_txt = :entrevistado)
            WHERE r.id_pregunta IS NULL";

        // 2. Filtro dinámico de Áreas (si no es "Todas")
        if (!empty($areas) && !in_array("Todas", $areas)) {
            $placeholders = implode(',', array_map(function ($k) {
                return ":area$k";
            }, array_keys($areas)));

            $sql .= " AND p.area IN ($placeholders)";
        }

        $stmt = Conexion::conectar()->prepare($sql);

        // Enlazamos el nombre del entrevistado para la validación de respuestas
        $stmt->bindParam(":entrevistado", $entrevistado, PDO::PARAM_STR);

        // Bind dinámico para las áreas seleccionadas
        if (!empty($areas) && !in_array("Todas", $areas)) {
            foreach ($areas as $k => $area) {
                $stmt->bindValue(":area$k", $area, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll();

        // Práctica Senior: Cerramos la conexión
        $stmt->close();
        $stmt = null;
    }

    /*=============================================
    MOSTRAR ÁREAS GLOBALES
    =============================================*/
    static public function mdlMostrarAreasUnicas($tabla)
    {
        // Eliminamos el WHERE usuario_asignado para que sea global
        $stmt = Conexion::conectar()->prepare("SELECT DISTINCT area FROM $tabla WHERE area IS NOT NULL AND area != '' ORDER BY area ASC");

        $stmt->execute();

        // Retornamos un array simple con los nombres de las áreas
        return $stmt->fetchAll(PDO::FETCH_COLUMN);

        $stmt->close();
        $stmt = null;
    }
}
