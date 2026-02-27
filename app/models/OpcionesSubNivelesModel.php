<?php
require_once __DIR__ . "/Conexion.php";

class ModeloMostrarSubNiveles
{
    //Mostrar flujos

    static public function mdlMostrarMostrarSubNiveles($tabla)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();
        $stmt = null;
    }
}
