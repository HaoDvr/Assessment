<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*=============================================
LOGICA PREVIA AL RENDERIZADO
=============================================*/
$tituloPagina = "Assessment";
$breadcrumbActivo = "Inicio";

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

    // 1. Solo cargamos menús si es Administrador
    if ($perfilUsuario == "admin") {
        include "app/views/components/NavBar.php";
        include "app/views/components/SideBar.php";
    }

    // 2. Si es usuario, quitamos el margen que deja el SideBar
    $estiloContenedor = ($perfilUsuario == "user") ? 'style="margin-left: 0px !important;"' : '';

    echo '<div class="content-wrapper" ' . $estiloContenedor . '>';

    // 3. Solo mostramos el cabezal de página (breadcrumb) al Admin
    if ($perfilUsuario == "admin") {
        include "app/views/components/ContentHeader.php";
    }

    if (isset($url)) {

        $paginas_validas = ["usuarios", "salir", "inicio", "fin"];

        if (in_array($url, $paginas_validas)) {

            /*=============================================
            FILTRO DE VISTA POR PERFIL
            =============================================*/
            if ($perfilUsuario == "admin") {

                $carpeta = explode('_', $url)[0];
                $ruta_archivo = "app/views/pages/" . $carpeta . "/" . $url . ".php";

                if (file_exists($ruta_archivo)) {
                    include $ruta_archivo;
                } else {
                    include "app/views/pages/errors/404.php";
                }
            } else {

                // RUTAS PERMITIDAS PARA EL USUARIO
                if ($url == "inicio") {
                    include "app/views/pages/inicio/inicio_usuario.php";
                } else if ($url == "salir") {
                    // Importante: Permitir que el usuario también pueda salir
                    include "app/views/pages/salir/salir.php";
                } else {
                    include "app/views/pages/errors/404.php";
                }
            }
        } else {
            include "app/views/pages/errors/404.php";
        }
    } else {

        /*=============================================
        CARGA POR DEFECTO (RAÍZ)
        =============================================*/
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
