<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title><?php echo isset($titulo) ? $titulo : 'Mi Dulce Antojo'; ?></title>
    <link rel="stylesheet" href="./assets/css/tabler.min.css">
    <link rel="stylesheet" href="./assets/css/EstilosPersonalizados.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <div class="page">
        <!-- --------------------------
        * Menu izqierda
        ------------------------------->
        <?php require_once '../app/views/layout/Menu.php'; ?>

        <div class="page-wrapper">
            <header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
                <div class="container-xl">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="navbar-nav flex-row order-md-last">
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown">
                                <span class="avatar avatar-sm" style="background-image: url(public/assets/img/king.png)"></span>
                                <div class="d-none d-xl-block ps-2">
                                    <div><?php echo $usuario; ?></div>
                                    <div class="mt-1 small text-secondary">Administrador</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </header>