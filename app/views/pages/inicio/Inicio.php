<?php
// 1. Instanciamos el controlador para obtener los datos
$inicio = new InicioController();
$totales = $inicio->ctrMostrarTotales();
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <div class="small-box bg-info shadow-sm" style="border-radius: 15px;">
                                    <div class="inner">
                                        <h3><?php echo number_format($totales["preguntas"]); ?></h3>
                                        <p><b>Preguntas en total</b></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa-regular fa-circle-question"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <div class="small-box bg-primary shadow-sm" style="border-radius: 15px;">
                                    <div class="inner">
                                        <h3><?php echo number_format($totales["usuarios"]); ?></h3>
                                        <p><b>Usuarios para encuentas</b></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <div class="small-box bg-success shadow-sm" style="border-radius: 15px;">
                                    <div class="inner">
                                        <h3><?php echo number_format($totales["respuestas"]); ?></h3>
                                        <p><b>Respuestas contestadas</b></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <div class="small-box bg-warning shadow-sm" style="border-radius: 15px;">
                                    <div class="inner">
                                        <h3><?php echo number_format($totales["respuestas"]); ?></h3>
                                        <p><b>Respuestas pendientes</b></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>