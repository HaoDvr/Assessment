<?php include "app/views/components/componentesUsuarios/NavBar.php"; ?>

<?php
// Formateamos el nombre del usuario para que siempre se vea bien
$nombreFormateado = mb_convert_case(mb_strtolower($_SESSION["nombre"]), MB_CASE_TITLE, "UTF-8");
?>

<style>
    .pagina-encuesta {
        border: none !important;
    }

    /* Estilo para resaltar campos inválidos en Bootstrap 4 */
    .form-control.is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }
</style>

<div class="content mt-4 pb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-9 text-center">

                <h2 class="text-muted">Bienvenido al sistema de evaluación</h2>
                <hr>

                <div class="d-flex justify-content-center mt-3">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center rounded-3 text-start py-2 mb-0"
                            role="alert"
                            style="background-color: #fff3cd; color: #856404; font-size: 0.85rem;">
                            <i class="fas fa-exclamation-triangle mr-2 text-warning" style="font-size: 1.2rem;"></i>
                            <div>
                                <strong>Recordatorio:</strong> Es necesario seleccionar una opción y completar los campos.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid d-flex justify-content-center pt-3">
                    <div class="row w-100 justify-content-center">
                        <div class="col-12 col-md-11 col-lg-10 px-0 px-sm-2">

                            <div class="card shadow p-2 p-sm-4 mb-4" style="border-radius: 20px; border: none;">

                                <div class="card-body text-center">
                                    <p class="card-text text-muted text-start small">
                                        Por favor conteste las siguientes preguntas para identificar el nivel de madurez. Seleccione una opción y después complete el campo libre.
                                    </p>

                                    <div class="progress mt-3" style="height: 10px; border-radius: 5px;">
                                        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;"></div>
                                    </div>

                                    <small id="progresoTexto" class="text-primary fw-bold d-block mt-1 text-end">Paso 1</small>
                                </div>

                                <div class="contieneFormulario text-start px-md-4">
                                    <form id="formularioMadurez" class="needs-validation" novalidate>

                                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION["id"]; ?>">
                                        <input type="hidden" name="token_respuesta" value="<?php echo bin2hex(random_bytes(8)); ?>">
                                        <input type="hidden" name="nombre_usuario_txt" value="<?php echo $nombreFormateado; ?>">

                                        <?php
                                        // Declaramos los arreglos para que el foreach no falle
                                        $preguntas = PreguntasControlador::ctrMostrarPreguntas($_SESSION["id"]);
                                        $respuestas = OpcionesRespuestaControlador::ctrMostrarOpcionesRespuesta();

                                        // Verificación de seguridad para evitar el error de "null"
                                        if (!is_array($preguntas)) {
                                            $preguntas = [];
                                        }

                                        $totalPreguntas = count($preguntas);
                                        $porPagina = 1;
                                        $numPaso = 1;

                                        foreach ($preguntas as $index => $pregunta) :
                                            $idPregunta = $pregunta["preguntas_final_id"];

                                            // Lógica de apertura de página (Pagination)
                                            if ($index % $porPagina == 0) {
                                                $claseVisible = ($index == 0) ? "" : "d-none";
                                                echo '<div class="pagina-encuesta ' . $claseVisible . '" id="paso-' . $numPaso . '">';
                                                $numPaso++;
                                            }
                                        ?>

                                            <label class="form-label text-left fw-bold text-secondary mb-4 mt-2 d-block" style="font-size: 1.1rem;">
                                                <?php echo ($index + 1); ?>.- ¿<?php echo $pregunta["pregunta"]; ?>?
                                            </label>

                                            <input type="hidden" name="respuestas[<?php echo $idPregunta; ?>][pregunta_txt]" value="<?php echo $pregunta["pregunta"]; ?>">

                                            <div class="bloque-respuestas mb-4">

                                                <div class="mb-4 text-left">
                                                    <label for="libre_<?php echo $idPregunta; ?>" class="text-secondary small fw-bold mb-1 ml-2 d-block">
                                                        <i class="fas fa-edit mr-1 text-primary"></i> Respuesta Detallada
                                                    </label>
                                                    <div class="shadow-sm" style="border-radius: 10px;">
                                                        <textarea class="form-control"
                                                            id="libre_<?php echo $idPregunta; ?>"
                                                            name="respuestas[<?php echo $idPregunta; ?>][libre]"
                                                            placeholder="Escriba aquí los detalles..."
                                                            style="height: 120px; border-radius: 10px; border: 1px solid #dee2e6; font-size: 0.9rem; padding: 15px;"
                                                            required></textarea>
                                                    </div>
                                                </div>

                                                <div class="filtros-seleccion mb-4 p-3 border rounded shadow-sm" style="background-color: #ffffff; border-radius: 15px !important;">
                                                    <div class="row">

                                                        <div class="col-12 mb-3 text-left">
                                                            <label class="small fw-bold text-primary mb-1 d-block">
                                                                <i class="fas fa-network-wired mr-1"></i> Dominio(s)
                                                            </label>
                                                            <div class="select2-primary">
                                                                <select class="form-control select2 select-dominio"
                                                                    multiple="multiple"
                                                                    data-placeholder="Seleccionar dominios..."
                                                                    name="respuestas[<?php echo $idPregunta; ?>][dominios][]"
                                                                    style="width: 100%;">
                                                                    <option value="RAN">RAN</option>
                                                                    <option value="CORE">CORE</option>
                                                                    <option value="DATACOM">DATACOM</option>
                                                                    <option value="INFRAESTRUCTURA">INFRAESTRUCTURA</option>
                                                                    <option value="TODOS">TODOS</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 text-left">
                                                            <label class="small fw-bold text-primary mb-1 d-block">
                                                                <i class="fas fa-concierge-bell mr-1"></i> Servicio(s)
                                                            </label>
                                                            <div class="select2-primary">
                                                                <select class="form-control select2 select-servicio"
                                                                    multiple="multiple"
                                                                    data-placeholder="Seleccionar servicios..."
                                                                    name="respuestas[<?php echo $idPregunta; ?>][servicios][]"
                                                                    style="width: 100%;">
                                                                    <option value="APLICACIONES">APLICACIONES</option>
                                                                    <option value="BACK OFFICE">BACK OFFICE</option>
                                                                    <option value="CANAL DIGITAL">CANAL DIGITAL</option>
                                                                    <option value="POSTPAGO">POSTPAGO</option>
                                                                    <option value="PREPAGO">PREPAGO</option>
                                                                    <option value="TODOS">TODOS</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="grupo-opciones mb-4">
                                                    <?php foreach ($respuestas as $respuesta) :
                                                        $idOpcion = $respuesta['id_opciones_respuestas'];
                                                        $inputId = "resp_" . $idPregunta . "_" . $idOpcion;
                                                    ?>
                                                        <div class="form-check border shadow-sm mb-3 d-flex align-items-center justify-content-start text-left py-3"
                                                            style="cursor: pointer; border-radius: 10px !important; position: relative; min-height: 60px;">

                                                            <input class="form-check-input" type="radio"
                                                                name="respuestas[<?php echo $idPregunta; ?>][id_seleccionada]"
                                                                id="<?php echo $inputId; ?>"
                                                                value="<?php echo $idOpcion; ?>"
                                                                data-texto="<?php echo $respuesta["descripcion_respuestas"]; ?>"
                                                                data-valor="<?php echo $respuesta["valor_respuesta"]; ?>"
                                                                style="width: 1.3rem; height: 1.3rem; cursor: pointer; position: absolute; left: 15px; top: 50%; transform: translateY(-50%); margin: 0;"
                                                                required>

                                                            <label class="form-check-label w-100 fw-medium text-dark mb-0 text-left"
                                                                for="<?php echo $inputId; ?>"
                                                                style="cursor: pointer; font-size: 0.85rem; line-height: 1.4; padding-left: 35px; padding-right: 10px;">
                                                                <?php echo $respuesta["descripcion_respuestas"]; ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>

                                                    <input type="hidden" name="respuestas[<?php echo $idPregunta; ?>][respuesta_txt]" id="txt_<?php echo $idPregunta; ?>">
                                                    <input type="hidden" name="respuestas[<?php echo $idPregunta; ?>][valor]" id="val_<?php echo $idPregunta; ?>">

                                                    <div class="invalid-feedback mt-2 fw-bold p-2 rounded-2 feedback-radio" style="font-size: 0.85rem; border: 1px solid #dc3545; background-color: #fff8f8; display: none;">
                                                        Debes seleccionar una opción para continuar.
                                                    </div>
                                                </div>

                                                <div class="mb-3 text-left">
                                                    <label for="detallada_<?php echo $idPregunta; ?>" class="text-secondary small fw-bold mb-1 ml-2 d-block">
                                                        <i class="fas fa-rocket mr-1 text-primary"></i> Iniciativa para automatización <span class="font-weight-light">(Opcional)</span>
                                                    </label>
                                                    <div class="shadow-sm" style="border-radius: 10px;">
                                                        <textarea class="form-control"
                                                            id="detallada_<?php echo $idPregunta; ?>"
                                                            name="respuestas[<?php echo $idPregunta; ?>][detallada]"
                                                            placeholder="Escriba aquí los detalles..."
                                                            style="height: 100px; border-radius: 10px; border: 1px solid #dee2e6; font-size: 0.9rem; padding: 15px;"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php
                                            // Lógica de cierre de página
                                            if (($index + 1) % $porPagina == 0 || ($index + 1) == $totalPreguntas) {
                                                echo '</div>';
                                            }
                                        endforeach; ?>

                                        <div class="d-flex justify-content-between p-4 mt-2">
                                            <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill px-4 d-none" id="btnAnterior">
                                                <i class="fas fa-chevron-left mr-2"></i> Anterior
                                            </button>
                                            <button type="button" class="btn btn-primary btn-lg rounded-pill px-4 ms-auto" id="btnSiguiente">
                                                Siguiente <i class="fas fa-chevron-right ml-2"></i>
                                            </button>
                                            <button type="submit" class="btn btn-success btn-lg shadow rounded-pill py-3 fw-bold d-none ms-auto" id="btnSubmit">
                                                <i class="fas fa-paper-plane mr-2"></i> Enviar Formulario
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>