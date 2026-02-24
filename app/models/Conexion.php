<?php

//LOCAL
class Conexion
{

    static public function conectar()
    {
        // Ajusta los datos según tu XAMPP
        $host = "localhost";
        $db   = "assessment"; // Asegúrate que así se llame tu DB
        $user = "root";
        $pass = ""; // Por defecto en XAMPP es vacío

        try {
            $link = new PDO(
                "mysql:host=$host;dbname=$db",
                $user,
                $pass,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
            );
            return $link;
        } catch (PDOException $e) {
            die("Error en la conexión: " . $e->getMessage());
        }
    }
}

//PROD
/*class Conexion
{

    static public function conectar()
    {
        // Ajusta los datos según tu XAMPP
        $host = "localhost";
        $db   = "sitetecs_assessment"; // Asegúrate que así se llame tu DB
        $user = "sitetecs_assessment";
        $pass = "@#$%assessment"; // Por defecto en XAMPP es vacío

        try {
            $link = new PDO(
                "mysql:host=$host;dbname=$db",
                $user,
                $pass,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
            );
            return $link;
        } catch (PDOException $e) {
            die("Error en la conexión: " . $e->getMessage());
        }
    }
}
*/
