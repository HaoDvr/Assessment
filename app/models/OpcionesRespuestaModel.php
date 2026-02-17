<?php
require_once __DIR__ . "/Conexion.php";

class ModeloMostrarOpcionesRespuesta
{
    //Mostrar flujos

    static public function mdlMostrarOpcionesRespuesta($tabla)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();
        $stmt = null;
    }
}
