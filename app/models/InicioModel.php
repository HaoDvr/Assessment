<?php
require_once __DIR__ . "/Conexion.php";

class ModeloInicio
{

    // Conteo fijo de preguntas
    static public function mdlContarPreguntas()
    {
        $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) as total FROM preguntas_final");
        $stmt->execute();
        return $stmt->fetch();
        $stmt = null;
    }

    // Conteo fijo de usuarios encuestados
    static public function mdlContarUsuariosEncuesta()
    {
        $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) as total FROM usuarios WHERE perfil_usuario = 'user'");
        $stmt->execute();
        return $stmt->fetch();
        $stmt = null;
    }

    // Conteo fijo de respuestas
    static public function mdlContarRespuestas()
    {
        $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) as total FROM respuestas_usuarios");
        $stmt->execute();
        return $stmt->fetch();
        $stmt = null;
    }
}
