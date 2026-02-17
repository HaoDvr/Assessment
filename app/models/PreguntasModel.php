<?php
require_once __DIR__ . "/Conexion.php";

class ModeloMostrarPreguntas
{
    static public function mdlMostrarPreguntasFiltradas($tabla, $entrevistado, $areas)
    {
        // 1. Query base
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

        /* =============================================
           ESTA ES LA UBICACIÓN CORRECTA DEL ORDER BY
        ============================================= */
        $sql .= " ORDER BY p.area ASC, p.preguntas_final_id ASC";

        $stmt = Conexion::conectar()->prepare($sql);

        // Enlazamos el nombre del entrevistado
        $stmt->bindParam(":entrevistado", $entrevistado, PDO::PARAM_STR);

        // Bind dinámico para las áreas
        if (!empty($areas) && !in_array("Todas", $areas)) {
            foreach ($areas as $k => $area) {
                $stmt->bindValue(":area$k", $area, PDO::PARAM_STR);
            }
        }

        $stmt->execute();

        // Guardamos resultados antes de cerrar para que no marque error
        $resultado = $stmt->fetchAll();

        // Práctica Senior: Cerramos la conexión correctamente
        $stmt->closeCursor(); // Usamos closeCursor en lugar de close
        $stmt = null;

        return $resultado;
    }

    /*=============================================
    MOSTRAR ÁREAS GLOBALES
    =============================================*/
    static public function mdlMostrarAreasUnicas($tabla)
    {
        $stmt = Conexion::conectar()->prepare("SELECT DISTINCT area FROM $tabla WHERE area IS NOT NULL AND area != '' ORDER BY area ASC");
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $stmt->closeCursor();
        $stmt = null;

        return $resultado;
    }
}
