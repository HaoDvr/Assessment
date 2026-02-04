<?php
//* Control de errores
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// **CORRECCIÓN DE RUTA:** Debes incluir el nombre del archivo de log
ini_set('error_log', "C:\xampp\htdocs\assestmen");

// **IMPORTANTE:** Define qué errores debe capturar y mostrar/loguear
error_reporting(E_ALL); // E_ALL incluye todos los tipos de errores, avisos y advertencias

//*Mandamos a llamar Controladores
require_once "app/controllers/TemplateController.php";
require_once "app/controllers/UsuariosController.php";
require_once "app/controllers/PreguntasController.php";
require_once "app/controllers/RespuestasController.php";
require_once "app/controllers/OpcionesRespuestasController.php";
require_once "app/controllers/InicioController.php";



//*Mandamos a llamar Modelos
require_once "app/models/UsuariosModel.php";
require_once "./app/models/PreguntasModel.php";
require_once "./app/models/RespuestasModel.php";
require_once "./app/models/OpcionesRespuestaModel.php";
require_once "./app/models/InicioModel.php";


//*Ocupo el Template controller
$template = new TemplateController();
$template->ctrTemplate();
