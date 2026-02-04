<?php
require_once "conexion.php";

class ModeloMostrarPreguntas
{
    //Mostrar flujos

    static public function mdlMostrarPreguntas($tabla)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla LIMIT 2");
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();
        $stmt = null;
    }
}
