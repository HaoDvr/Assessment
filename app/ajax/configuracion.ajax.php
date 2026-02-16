<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST["areas"]) && isset($_POST["entrevistado"])) {
    // Guardamos los filtros en la sesión
    $_SESSION["areas_seleccionadas"] = $_POST["areas"];
    $_SESSION["entrevistado_actual"] = $_POST["entrevistado"];

    echo "ok";
} else {
    echo "error";
}
