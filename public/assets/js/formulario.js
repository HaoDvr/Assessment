document.addEventListener("DOMContentLoaded", function () {
    /*=============================================
    1. INICIALIZACIÓN GLOBAL (Select2)
    =============================================*/
    // Se ejecuta en todas las páginas para que el estilo de AdminLTE/Bootstrap4 funcione siempre
    if (typeof $.fn.select2 !== "undefined") {
        $(".select2").select2({
            theme: "bootstrap4",
            allowClear: true,
            width: "100%",
            placeholder: "Selecciona opciones",
            containerCssClass: ":all:",
        });

        // Limpiar el borde rojo de Select2 al seleccionar algo
        $(".select2").on("change", function () {
            const container = $(this)
                .next(".select2-container")
                .find(".select2-selection");
            container.css("border", "1px solid #ced4da");
        });
    }

    /*=============================================
    2. VALIDACIÓN DE EXISTENCIA DEL FORMULARIO
    =============================================*/
    const form = document.getElementById("formularioMadurez");

    // Si no es la página de la encuesta (como en seleccion_area), detenemos la lógica del formulario aquí
    if (!form) return;

    /*=============================================
    3. VARIABLES Y ELEMENTOS DEL FORMULARIO
    =============================================*/
    let pasoActual = 1;
    const paginas = document.querySelectorAll(".pagina-encuesta");
    const totalPasos = paginas.length;

    const btnSiguiente = document.getElementById("btnSiguiente");
    const btnAnterior = document.getElementById("btnAnterior");
    const btnSubmit = document.getElementById("btnSubmit");
    const progressBar = document.getElementById("progressBar");
    const progresoTexto = document.getElementById("progresoTexto");

    // --- LIMPIEZA DINÁMICA DE ERRORES (Tiempo Real) ---
    form.addEventListener("input", function (event) {
        if (event.target.tagName === "TEXTAREA") {
            if (event.target.value.trim() !== "") {
                event.target.classList.remove("is-invalid");
            }
        }
    });

    form.addEventListener("change", function (event) {
        if (event.target.type === "radio") {
            const nameAttr = event.target.name;
            const match = nameAttr.match(/\[(\d+)\]/);
            if (match) {
                const idPregunta = match[1];
                const txtInput = document.getElementById("txt_" + idPregunta);
                const valInput = document.getElementById("val_" + idPregunta);
                if (txtInput)
                    txtInput.value =
                        event.target.getAttribute("data-texto") || "";
                if (valInput)
                    valInput.value =
                        event.target.getAttribute("data-valor") || "";
            }
            const grupo = event.target.closest(".grupo-opciones");
            if (grupo) {
                const feedback = grupo.querySelector(".invalid-feedback");
                if (feedback) feedback.style.display = "none";
            }
        }
    });

    // --- FUNCIÓN DE VALIDACIÓN REFORZADA ---
    function validarPasoActual() {
        const pasoVisible = document.getElementById("paso-" + pasoActual);
        if (!pasoVisible) return false;

        let esValido = true;
        let mensajeError = "";

        // A. Validar Radios
        const gruposRadio = pasoVisible.querySelectorAll(".grupo-opciones");
        gruposRadio.forEach((grupo) => {
            const radios = grupo.querySelectorAll('input[type="radio"]');
            const feedback = grupo.querySelector(".invalid-feedback");
            const seleccionado = Array.from(radios).some((r) => r.checked);
            if (!seleccionado) {
                esValido = false;
                mensajeError = "Debes seleccionar una opción de la lista.";
                if (feedback) feedback.style.display = "block";
            }
        });

        // B. Validar Textareas Required
        const textareasRequired =
            pasoVisible.querySelectorAll("textarea[required]");
        textareasRequired.forEach((area) => {
            if (area.value.trim() === "") {
                esValido = false;
                mensajeError = "La respuesta detallada es obligatoria.";
                area.classList.add("is-invalid");
            } else {
                area.classList.remove("is-invalid");
            }
        });

        // C. VALIDAR SELECT2 (Dominio y Servicio)
        const selectsMulti = pasoVisible.querySelectorAll("select.select2");
        selectsMulti.forEach((select) => {
            const val = $(select).val();
            const container = $(select)
                .next(".select2-container")
                .find(".select2-selection");

            if (!val || val.length === 0) {
                esValido = false;
                mensajeError = "Selecciona al menos un Dominio y un Servicio.";
                container.css("border", "1px solid #dc3545");
            } else {
                container.css("border", "1px solid #ced4da");
            }
        });

        if (!esValido) {
            Swal.fire({
                icon: "warning",
                title: "Atención",
                text: mensajeError,
                confirmButtonColor: "#3085d6",
            });
        }

        return esValido;
    }

    // --- NAVEGACIÓN ---
    function actualizarInterfaz() {
        paginas.forEach((p, i) => {
            p.classList.toggle("d-none", i + 1 !== pasoActual);
        });

        const porcentaje = (pasoActual / totalPasos) * 100;
        if (progressBar) progressBar.style.width = porcentaje + "%";
        if (progresoTexto)
            progresoTexto.innerText = `Paso ${pasoActual} de ${totalPasos}`;

        if (btnAnterior)
            btnAnterior.classList.toggle("d-none", pasoActual === 1);

        if (pasoActual === totalPasos) {
            if (btnSiguiente) btnSiguiente.classList.add("d-none");
            if (btnSubmit) btnSubmit.classList.remove("d-none");
        } else {
            if (btnSiguiente) btnSiguiente.classList.remove("d-none");
            if (btnSubmit) btnSubmit.classList.add("d-none");
        }
        window.scrollTo({ top: 0, behavior: "smooth" });
    }

    if (btnSiguiente) {
        btnSiguiente.addEventListener("click", () => {
            if (validarPasoActual()) {
                pasoActual++;
                actualizarInterfaz();
            }
        });
    }

    if (btnAnterior) {
        btnAnterior.addEventListener("click", () => {
            if (pasoActual > 1) {
                pasoActual--;
                actualizarInterfaz();
            }
        });
    }

    // --- ENVÍO AJAX ---
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        if (validarPasoActual()) {
            enviarFormularioAjax();
        }
    });

    function enviarFormularioAjax() {
        const datos = new FormData(form);
        $.ajax({
            url: "app/ajax/enviaFormulario.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                if (btnSubmit) {
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML =
                        '<span class="spinner-border spinner-border-sm"></span> Enviando...';
                }
            },
            success: function (respuesta) {
                if (respuesta.trim() === "ok") {
                    Swal.fire({
                        title: "¡Éxito!",
                        text: "Evaluación registrada correctamente.",
                        icon: "success",
                        confirmButtonText: "Aceptar",
                    }).then(() => {
                        window.location = "salir";
                    });
                } else {
                    Swal.fire(
                        "Error",
                        "No se pudo guardar: " + respuesta,
                        "error",
                    );
                    if (btnSubmit) {
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML =
                            '<i class="fas fa-paper-plane mr-2"></i> Enviar Formulario';
                    }
                }
            },
        });
    }

    // Ejecución inicial de la interfaz
    actualizarInterfaz();
});
