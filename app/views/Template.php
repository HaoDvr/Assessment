<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*=============================================
LOGICA PREVIA AL RENDERIZADO
=============================================*/
$tituloPagina = "Assessment";
$breadcrumbActivo = "Inicio";

// Definimos la URL base para usarla en todo el sitio
// Si estás en la raíz, deja solo "/"
$url_base = "/";

if (isset($_GET["url"])) {
    $url = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET["url"]);
}

/*=============================================
VALIDACIÓN DE SEGURIDAD
=============================================*/
if (isset($url) && (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] != "ok")) {
    if ($url != "login") {
        echo '<script>window.location = "login?fallo=true";</script>';
        exit();
    }
}

/*=============================================
RENDERIZADO DE LA PAGINA
=============================================*/
include "app/views/components/Header.php";

if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {

    $perfilUsuario = strtolower(trim($_SESSION["perfil"] ?? "user"));

    // 1. Menús de Administración
    if ($perfilUsuario == "admin") {
        include "app/views/components/NavBar.php";
        include "app/views/components/SideBar.php";
    }

    $estiloContenedor = ($perfilUsuario == "user") ? 'style="margin-left: 0px !important;"' : '';

    echo '<div class="content-wrapper" ' . $estiloContenedor . '>';

    if ($perfilUsuario == "admin") {
        include "app/views/components/ContentHeader.php";
    }

    if (isset($url)) {

        $paginas_validas = ["usuarios", "salir", "inicio", "fin", "seleccion"];

        if (in_array($url, $paginas_validas)) {

            /*=============================================
            RUTAS GLOBALES (Para ambos perfiles)
            =============================================*/
            if ($url == "salir") {
                // Forzamos la ruta exacta del archivo salir
                include "app/views/pages/salir/salir.php";
            } else if ($perfilUsuario == "admin") {

                /*=============================================
                LOGICA ADMIN
                =============================================*/
                $carpeta = explode('_', $url)[0];
                $ruta_archivo = "app/views/pages/" . $carpeta . "/" . $url . ".php";

                if (file_exists($ruta_archivo)) {
                    include $ruta_archivo;
                } else {
                    include "app/views/pages/errors/404.php";
                }
            } else {

                /*=============================================
                LOGICA USUARIO
                =============================================*/
                if ($url == "inicio") {
                    if (!isset($_SESSION["areas_seleccionadas"])) {
                        include "app/views/pages/seleccion_area/seleccion_area.php";
                    } else {
                        include "app/views/pages/inicio/inicio_usuario.php";
                    }
                } else if ($url == "seleccion") {
                    include "app/views/pages/seleccion_area/seleccion_area.php";
                } else {
                    include "app/views/pages/errors/404.php";
                }
            }
        } else {
            include "app/views/pages/errors/404.php";
        }
    } else {
        // CARGA POR DEFECTO
        if ($perfilUsuario == "admin") {
            include "app/views/pages/inicio/inicio.php";
        } else {
            include "app/views/pages/inicio/inicio_usuario.php";
        }
    }

    echo '</div>';
    include "app/views/components/Footer.php";
} else {
    include "app/views/pages/login/login.php";
}
