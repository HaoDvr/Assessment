<?php
$tabla = "preguntas_final";
$areasDisponibles = ModeloMostrarPreguntas::mdlMostrarAreasUnicas($tabla);
include "app/views/components/componentesUsuarios/NavBar.php";
?>

<style>
    /* Ajuste para que el Select2 se adapte al ancho del contenedor */
    .select2-container--bootstrap4 .select2-selection--multiple {
        border: 1px solid #ced4da !important;
        border-radius: .25rem !important;
    }

    .pagina-encuesta {
        border: none !important;
    }
</style>

<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card card-outline card-primary shadow-lg" style="width: 100%; max-width: 550px;">
        <div class="card-header text-center">
            <h3 class="card-title float-none"><b>Configuración de la Entrevista</b></h3>
        </div>

        <div class="card-body">
            <div class="form-group">
                <label>1. Selecciona las Áreas a evaluar:</label>
                <select class="form-control select2" id="selectAreas" multiple="multiple" data-placeholder="Puedes elegir varias áreas" style="width: 100%;">
                    <option value="Todas">-- Todas las Áreas --</option>
                    <?php foreach ($areasDisponibles as $area): ?>
                        <option value="<?php echo $area; ?>"><?php echo $area; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group mt-4">
                <label>2. Nombre de la persona entrevistada:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                    </div>
                    <input type="text" id="nombreEntrevistado" class="form-control" placeholder="Escribe el nombre completo..." required>
                </div>
                <small class="text-muted text-italic">* Este nombre se guardará en el registro de respuestas.</small>
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="button" id="btnIniciarEvaluacion" class="btn btn-primary btn-block btn-lg">
                Comenzar Evaluación <i class="fas fa-play-circle ml-2"></i>
            </button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // No inicializamos Select2 aquí, ya lo hace formulario.js de forma global

        $('#btnIniciarEvaluacion').on('click', function() {
            const areas = $('#selectAreas').val();
            const entrevistado = $('#nombreEntrevistado').val().trim();

            if (areas.length === 0 || entrevistado === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor, selecciona las áreas y escribe el nombre del entrevistado.'
                });
                return;
            }

            $.ajax({
                url: "app/ajax/configuracion.ajax.php",
                method: "POST",
                data: {
                    areas: areas,
                    entrevistado: entrevistado
                },
                success: function(respuesta) {
                    if (respuesta.trim() === "ok") {
                        window.location = "inicio";
                    } else {
                        console.error("Error en AJAX:", respuesta);
                    }
                }
            });
        });
    });
</script>