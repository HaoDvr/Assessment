<?php
/*=============================================
LOGICA PREVIA AL RENDERIZADO 
=============================================*/

// 1. Valores base (Se definen UNA sola vez)
$tituloPagina = "Assestmen";
$breadcrumbActivo = "Inicio";

// 2. Intentamos mejorar esos valores si hay una URL válida
if (isset($_GET["url"])) {

    $url = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET["url"]);
    $nombreControlador = ucfirst($url) . "Controller";

    if (class_exists($nombreControlador)) {

        $controller = new $nombreControlador();
        $paginaConfig = $controller->index();

        // Sobreescribimos los valores base con los del controlador
        $tituloPagina = $paginaConfig['titulo'];
        $breadcrumbActivo = $paginaConfig['breadcrumbActivo'];
    }
    // NOTA: Ya no necesitamos el "else" aquí, porque si no entra, 
    // se quedan los valores que pusimos al principio del archivo.
}

/*=============================================
RENDERIZADO DE LA PAGINA
=============================================*/
include "app/views/components/Header.php";
include "app/views/components/NavBar.php";
include "app/views/components/SideBar.php";
?>

<div class="content-wrapper">

    <?php

    include "app/views/components/ContentHeader.php";

    if (isset($url)) {

        $paginas_validas = ["usuarios"];

        if (in_array($url, $paginas_validas)) {

            $carpeta = explode('_', $url)[0];

            $ruta_archivo = "app/views/pages/" . $carpeta . "/" . $url . ".php";

            if (file_exists($ruta_archivo)) {
                include $ruta_archivo;
            } else {
                include "app/views/pages/errors/404.php";
            }
        } else {
            include "app/views/pages/errors/404.php";
        }
    } else {
        include "app/views/pages/inicio/Inicio.php";
    }
    ?>

</div>

<?php include "app/views/components/Footer.php"; ?>