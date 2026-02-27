<?php
//* Control de errores
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// CORRECCIÓN SENIOR: En Linux no existe "C:\".
// Usamos una ruta relativa al servidor o lo comentamos para que use el log por defecto de cPanel.
ini_set('error_log', __DIR__ . "/php_error.log");

error_reporting(E_ALL);

//* Mandamos a llamar Controladores con __DIR__ para máxima compatibilidad
require_once __DIR__ . "/app/controllers/TemplateController.php";
require_once __DIR__ . "/app/controllers/UsuariosController.php";
require_once __DIR__ . "/app/controllers/PreguntasController.php";
require_once __DIR__ . "/app/controllers/RespuestasController.php";
require_once __DIR__ . "/app/controllers/OpcionesRespuestasController.php";
require_once __DIR__ . "/app/controllers/InicioController.php";
require_once __DIR__ . "/app/controllers/OpcionesSubNivelesController.php";

//* Mandamos a llamar Modelos
require_once __DIR__ . "/app/models/UsuariosModel.php";
require_once __DIR__ . "/app/models/PreguntasModel.php";
require_once __DIR__ . "/app/models/RespuestasModel.php";
require_once __DIR__ . "/app/models/OpcionesRespuestaModel.php";
require_once __DIR__ . "/app/models/InicioModel.php";
require_once __DIR__ . "/app/models/OpcionesSubNivelesModel.php";

//* Instanciamos el Template
$template = new TemplateController();
$template->ctrTemplate();
