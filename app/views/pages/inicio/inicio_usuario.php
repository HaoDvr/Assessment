<?php include "app/views/components/componentesUsuarios/NavBar.php"; ?>

<?php
$nombreFormateado = mb_convert_case(mb_strtolower($_SESSION["nombre"]), MB_CASE_TITLE, "UTF-8");
$areasElegidas = $_SESSION["areas_seleccionadas"] ?? [];
$esTodas = in_array("Todas", $areasElegidas);
$entrevistadoActual = $_SESSION["entrevistado_actual"] ?? 'No definido';
?>

<style>
    .pagina-encuesta {
        border: none !important;
    }

    /* --- PUNTO 1: RADIOS PERFECTOS --- */
    .form-check {
        padding-left: 0 !important;
        margin-bottom: 12px;
        border: 1px solid #e9ecef;
        border-radius: 12px !important;
        position: relative;
        display: flex;
        align-items: center;
        background-color: #ffffff;
        transition: all 0.2s ease;
    }

    .form-check-input {
        width: 1.25rem !important;
        height: 1.25rem !important;
        margin: 0 !important;
        cursor: pointer;
        position: absolute !important;
        left: 20px !important;
        /* Separación del borde izquierdo */
        top: 50% !important;
        transform: translateY(-50%) !important;
    }

    .form-check-label {
        cursor: pointer;
        font-size: 0.85rem;
        width: 100%;
        text-align: left !important;
        /* 20px de margen + 20px de radio + 15px de separación = 55px */
        padding: 15px 15px 15px 55px !important;
        line-height: 1.4;
        color: #495057;
    }

    .form-check:hover {
        background-color: #f8f9fa;
        border-color: #007bff !important;
    }

    /* --- PUNTO 2: BOTONES COMPACTOS --- */
    .btn-nav-custom {
        padding: 0.4rem 1.2rem !important;
        font-size: 0.9rem !important;
        font-weight: 600;
        min-width: 100px;
    }

    /* --- ESTILO PROFESIONAL REASIGNACIÓN --- */
    .area-reasignar {
        display: none;
        /* Nace oculto */
        background-color: #fdf2f2;
        border-left: 5px solid #dc3545;
        border-radius: 10px;
        padding: 20px;
        margin: 15px 0 25px 0;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.1);
    }

    .badge-area {
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
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
                        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center rounded-3 text-start py-2 mb-0" role="alert" style="background-color: #fff3cd; color: #856404; font-size: 0.85rem;">
                            <i class="fas fa-exclamation-triangle mr-2 text-warning" style="font-size: 1.2rem;"></i>
                            <div><strong>Recordatorio:</strong> Es necesario seleccionar una opción y completar los campos.</div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid d-flex justify-content-center pt-3">
                    <div class="row w-100 justify-content-center">
                        <div class="col-12 col-md-11 col-lg-10 px-0 px-sm-2">
                            <div class="card shadow p-2 p-sm-4 mb-4" style="border-radius: 20px; border: none;">
                                <div class="card-body text-center">
                                    <p class="card-text text-muted text-start small">Por favor conteste las siguientes preguntas para identificar el nivel de madurez.</p>
                                    <div class="progress mt-3" style="height: 10px; border-radius: 5px;">
                                        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted italic">Entrevistado: <b><?php echo $entrevistadoActual; ?></b></small>
                                        <small id="progresoTexto" class="text-primary fw-bold">Paso 1</small>
                                    </div>
                                </div>

                                <div class="contieneFormulario text-start px-md-4">
                                    <form id="formularioMadurez" class="needs-validation" novalidate>
                                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION["id"]; ?>">
                                        <input type="hidden" name="token_respuesta" value="<?php echo bin2hex(random_bytes(8)); ?>">
                                        <input type="hidden" name="nombre_usuario_txt" value="<?php echo $nombreFormateado; ?>">
                                        <input type="hidden" name="entrevistados_json" value='<?php echo json_encode([$entrevistadoActual]); ?>'>

                                        <?php
                                        $preguntas = PreguntasControlador::ctrMostrarPreguntas();
                                        $respuestas = OpcionesRespuestaControlador::ctrMostrarOpcionesRespuesta();
                                        $respuestasSubNiveles = OpcionesSubNivelesController::ctrMostrarOpcionesSubNiveles();
                                        if (!is_array($preguntas)) $preguntas = [];

                                        $totalPreguntas = count($preguntas);
                                        $porPagina = 1;
                                        $numPaso = 1;

                                        foreach ($preguntas as $index => $pregunta) :
                                            $idPregunta = $pregunta["preguntas_final_id"];
                                            $areaActual = $pregunta["area"];

                                            if ($index % $porPagina == 0) {
                                                $claseVisible = ($index == 0) ? "" : "d-none";
                                                echo '<div class="pagina-encuesta ' . $claseVisible . '" id="paso-' . $numPaso . '" data-id-original="' . $idPregunta . '">';

                                                $tipoEncuesta = $esTodas ? "Cuestionario Completo" : "Evaluación por Área";
                                                $claseBadge = $esTodas ? "bg-success" : "bg-primary";
                                                echo '<div class="mb-4 pb-3 border-bottom">
                                                        <span class="badge ' . $claseBadge . ' text-white badge-area mb-2"><i class="fas fa-clipboard-list mr-1"></i> ' . $tipoEncuesta . '</span>
                                                        <h3 class="text-dark font-weight-bold mb-0">Preguntas de <span class="text-primary">' . $areaActual . '</span></h3>
                                                      </div>';
                                                $numPaso++;
                                            }
                                        ?>
                                            <label class="form-label text-left fw-bold text-secondary my-2 d-block" style="font-size: 1.1rem;">
                                                <?php echo ($index + 1); ?>.- ¿<?php echo $pregunta["pregunta"]; ?>?
                                            </label>

                                            <div class="contenedor-flujos text-left mb-2">
                                                <div class="badge badge-secondary"><?php echo $pregunta["nombre_flujo"]; ?></div>
                                                <div class="badge badge-secondary"><?php echo $pregunta["nombre_sub_flujo"]; ?></div>
                                                <div class="badge badge-secondary"><?php echo $pregunta["categoria_tarea"]; ?></div>
                                            </div>

                                            <div class="custom-control custom-checkbox my-3">
                                                <input type="checkbox" class="custom-control-input chk-no-responde" id="skip_<?php echo $idPregunta; ?>" name="respuestas[<?php echo $idPregunta; ?>][omitir]" value="si">
                                                <label class="custom-control-label text-danger font-weight-bold" for="skip_<?php echo $idPregunta; ?>" style="cursor:pointer;">El usuario no responderá esta pregunta</label>
                                            </div>

                                            <div id="area_reasignar_<?php echo $idPregunta; ?>" class="area-reasignar shadow-sm">
                                                <div class="form-group mb-3">
                                                    <label class="small font-weight-bold text-dark">Área / Usuario a reasignar:</label>
                                                    <input type="text" class="form-control" name="respuestas[<?php echo $idPregunta; ?>][usuario_reasignado]" placeholder="Ej: Redes / Juan Pérez">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="small font-weight-bold text-dark">Comentario de reasignación (máx 100 palabras):</label>
                                                    <textarea class="form-control" name="respuestas[<?php echo $idPregunta; ?>][motivo_omision]" placeholder="Escriba el motivo aquí..." style="height: 80px; border-radius: 8px;"></textarea>
                                                </div>
                                            </div>

                                            <div class="bloque-respuestas-principal">
                                                <input type="hidden" name="respuestas[<?php echo $idPregunta; ?>][pregunta_txt]" value="<?php echo $pregunta["pregunta"]; ?>">
                                                <input type="hidden" name="respuestas[<?php echo $idPregunta; ?>][area]" value="<?php echo $areaActual; ?>">

                                                <div class="mb-4 text-left">
                                                    <label for="libre_<?php echo $idPregunta; ?>" class="text-secondary small fw-bold mb-1 ml-2 d-block">
                                                        <i class="fas fa-edit mr-1 text-primary"></i> Respuesta Detallada
                                                    </label>
                                                    <textarea class="form-control" id="libre_<?php echo $idPregunta; ?>" name="respuestas[<?php echo $idPregunta; ?>][libre]" required style="height: 120px; border-radius: 10px;"></textarea>
                                                </div>

                                                <div class="mb-4 text-left">
                                                    <label class="text-secondary small fw-bold mb-2 ml-2 d-block">
                                                        <i class="fas fa-chart-line mr-1 text-primary"></i> Grado de Frecuencia / Aplicación
                                                    </label>

                                                    <div class="d-flex flex-wrap justify-content-between">
                                                        <?php foreach ($respuestasSubNiveles as $subNivel) :
                                                            // Generamos un ID único combinando ID de pregunta + ID de sub-nivel
                                                            $subId = "sub_" . $idPregunta . "_" . $subNivel["sub_niveles_id"];
                                                        ?>
                                                            <div class="form-check border shadow-sm mb-2" style="width: 49%; min-width: 250px;">

                                                                <input class="form-check-input" type="radio"
                                                                    name="respuestas[<?php echo $idPregunta; ?>][valor_sub_nivel]"
                                                                    id="<?php echo $subId; ?>"
                                                                    value="<?php echo $subNivel["valor_sub_nivel"]; ?>">

                                                                <label class="form-check-label" for="<?php echo $subId; ?>">
                                                                    <?php echo $subNivel["txt_sub_nivel"]; ?>
                                                                </label>

                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>

                                                <div class="filtros-seleccion mb-4 p-3 border rounded shadow-sm bg-white" style="border-radius: 15px !important;">
                                                    <div class="row">
                                                        <div class="col-12 mb-3 text-left">
                                                            <label class="small fw-bold text-primary mb-1 d-block"><i class="fas fa-network-wired mr-1"></i> Dominio(s)</label>
                                                            <select class="form-control select2 select-dominio" multiple="multiple" name="respuestas[<?php echo $idPregunta; ?>][dominios][]" required>
                                                                <option value="RAN">RAN</option>
                                                                <option value="CORE">CORE</option>
                                                                <option value="DATACOM">DATACOM</option>
                                                                <option value="INFRAESTRUCTURA">INFRAESTRUCTURA</option>
                                                                <option value="TODOS">TODOS</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 text-left">
                                                            <label class="small fw-bold text-primary mb-1 d-block"><i class="fas fa-concierge-bell mr-1"></i> Servicio(s)</label>
                                                            <select class="form-control select2 select-servicio" multiple="multiple" name="respuestas[<?php echo $idPregunta; ?>][servicios][]" required>
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

                                                <div class="grupo-opciones mb-4">
                                                    <?php foreach ($respuestas as $respuesta) :
                                                        $idOpcion = $respuesta['id_opciones_respuestas'];
                                                        $inputId = "resp_" . $idPregunta . "_" . $idOpcion;
                                                    ?>
                                                        <div class="form-check border shadow-sm mb-3">
                                                            <input class="form-check-input" type="radio" name="respuestas[<?php echo $idPregunta; ?>][id_seleccionada]" id="<?php echo $inputId; ?>" value="<?php echo $idOpcion; ?>" data-texto="<?php echo $respuesta["descripcion_respuestas"]; ?>" data-valor="<?php echo $respuesta["valor_respuesta"]; ?>" required>
                                                            <label class="form-check-label" for="<?php echo $inputId; ?>"><?php echo $respuesta["descripcion_respuestas"]; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                    <input type="hidden" name="respuestas[<?php echo $idPregunta; ?>][respuesta_txt]" id="txt_<?php echo $idPregunta; ?>">
                                                    <input type="hidden" name="respuestas[<?php echo $idPregunta; ?>][valor]" id="val_<?php echo $idPregunta; ?>">
                                                    <div class="invalid-feedback feedback-radio">Debes seleccionar una opción.</div>
                                                </div>

                                                <div class="mb-3 text-left">
                                                    <label for="detallada_<?php echo $idPregunta; ?>" class="text-secondary small fw-bold mb-1 ml-2 d-block">
                                                        <i class="fas fa-rocket mr-1 text-primary"></i> Iniciativa para automatización <span class="font-weight-light">(Opcional)</span>
                                                    </label>
                                                    <textarea class="form-control" id="detallada_<?php echo $idPregunta; ?>" name="respuestas[<?php echo $idPregunta; ?>][detallada]" placeholder="Escriba aquí los detalles..." style="height: 100px; border-radius: 10px;"></textarea>
                                                </div>
                                            </div>

                                        <?php
                                            if (($index + 1) % $porPagina == 0 || ($index + 1) == $totalPreguntas) {
                                                echo '</div>';
                                            }
                                        endforeach; ?>

                                        <div class="d-flex flex-wrap justify-content-between p-4 mt-2">
                                            <button type="button" class="btn btn-outline-secondary btn-nav-custom rounded-pill d-none" id="btnAnterior">
                                                <i class="fas fa-chevron-left mr-2"></i> Anterior
                                            </button>

                                            <button type="button" class="btn btn-warning btn-nav-custom rounded-pill mx-1" id="btnDuplicar">
                                                <i class="fas fa-copy mr-2"></i> Duplicar
                                            </button>

                                            <button type="button" class="btn btn-primary btn-nav-custom rounded-pill ms-auto" id="btnSiguiente">
                                                Siguiente <i class="fas fa-chevron-right ml-2"></i>
                                            </button>

                                            <button type="submit" class="btn btn-success btn-nav-custom shadow rounded-pill fw-bold d-none ms-auto" id="btnSubmit">
                                                <i class="fas fa-paper-plane mr-2"></i> Enviar
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