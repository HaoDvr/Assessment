<?php

class  OpcionesSubNivelesController
{

    static public function ctrMostrarOpcionesSubNiveles()
    {

        $tabla = "sub_niveles";
        $respuesta = ModeloMostrarSubNiveles::mdlMostrarMostrarSubNiveles($tabla);
        return $respuesta;
    }
}
