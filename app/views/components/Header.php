<?php
// 1. Enviamos el header por PHP (esto siempre funciona si no hay HTML antes)
header("Content-Security-Policy: default-src 'self' https://fonts.gstatic.com https://fonts.googleapis.com; connect-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.googleapis.com; img-src 'self' data:; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net;");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https://fonts.gstatic.com https://fonts.googleapis.com; connect-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.googleapis.com; img-src 'self' data:; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net;">

    <title>Assessment | <?php echo isset($tituloPagina) ? $tituloPagina : "Inicio"; ?></title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/css/OverlayScrollbars.min.css">

    <link rel="stylesheet" href="public/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="public/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="public/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link rel="stylesheet" href="public/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="public/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <link rel="stylesheet" href="public/assets/css/adminlte.min.css">
    <link rel="stylesheet" href="public/assets/css/configuracion.css">
</head>

<body class="hold-transition <?php echo (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") ? 'sidebar-mini layout-fixed' : 'login-page'; ?>">

    <?php if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok"): ?>
        <div class="wrapper">
        <?php endif; ?>