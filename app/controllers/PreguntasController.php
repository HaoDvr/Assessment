<?php
class PreguntasControlador
{
    static public function ctrMostrarPreguntas()
    {
        $tabla = "preguntas_final";

        // 1. Usamos el nombre del entrevistado para filtrar las preguntas que YA respondió
        // Si no existe, usamos un string vacío para que no truene el query
        $entrevistado = isset($_SESSION["entrevistado_actual"]) ? $_SESSION["entrevistado_actual"] : "";

        // 2. Obtenemos las áreas que se eligieron en el paso previo
        $areasElegidas = isset($_SESSION["areas_seleccionadas"]) ? $_SESSION["areas_seleccionadas"] : [];

        // 3. Llamamos al modelo con el nombre del ENTREVISTADO para que el JOIN
        // de respuestas sepa qué preguntas ocultar para ESTA entrevista
        $respuesta = ModeloMostrarPreguntas::mdlMostrarPreguntasFiltradas($tabla, $entrevistado, $areasElegidas);

        return $respuesta;
    }
}
