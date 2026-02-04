<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h1"><b>Assess</b>ment</a>
        </div>
        <div class="card-body">

            <?php
            /*=============================================
            MENSAJE DE SEGURIDAD (ACCESO NO AUTORIZADO)
            =============================================*/
            // Si el usuario intentó entrar a una página interna y no hay sesión activa
            if (isset($_GET["fallo"]) && $_GET["fallo"] != "login" && (!isset($_SESSION["iniciarSesion"]))) {
                echo '<div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                        <small>
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <b>Acceso denegado:</b> Debe iniciar sesión primero para acceder al sistema.
                        </small>
                      </div>';
            }
            ?>

            <p class="login-box-msg">Inicia sesión para acceder al sistema</p>

            <form method="post">
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Email" name="ingUsuario" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Password" name="ingPassword" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                    </div>
                </div>

                <?php
                // Instancia del controlador para procesar el post
                $login = new UsuariosController();
                $login->ctrIngresoUsuario();
                ?>
            </form>

            <hr>

            <div class="alert alert-info text-center py-2" role="alert" style="font-size: 0.85rem;">
                Si olvidó su password y/o usuario por favor contactar a un <b>administrador de NTTDATA-TELCO</b>
            </div>

        </div>
    </div>
</div>