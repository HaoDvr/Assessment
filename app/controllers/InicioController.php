<?php

class InicioController
{

    static public function ctrMostrarTotales()
    {

        // Llamadas directas a los nuevos métodos del modelo
        $totalPreguntas = ModeloInicio::mdlContarPreguntas();
        $totalUsuarios  = ModeloInicio::mdlContarUsuariosEncuesta();
        $totalRespuestas = ModeloInicio::mdlContarRespuestas();

        return [
            "preguntas"  => $totalPreguntas["total"],
            "usuarios"   => $totalUsuarios["total"],
            "respuestas" => $totalRespuestas["total"]
        ];
    }
}
