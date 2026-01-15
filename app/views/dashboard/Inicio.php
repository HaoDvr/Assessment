<?php require_once '../app/views/layout/Header.php'; ?>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Panel de Control
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Bienvenido de nuevo, <?php echo $usuario; ?></h3>
                        <p>Este es tu nuevo panel administrativo con diseño Fluid Vertical.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layout/Footer.php'; ?>