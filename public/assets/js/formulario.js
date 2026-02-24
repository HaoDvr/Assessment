document.addEventListener("DOMContentLoaded", function () {
    /*=============================================
    1. INICIALIZACIÓN GLOBAL (Select2)
    =============================================*/
    function initSelect2(selector) {
        if (typeof $.fn.select2 !== "undefined") {
            $(selector).select2({
                theme: "bootstrap4",
                allowClear: true,
                width: "100%",
                placeholder: "Selecciona opciones",
            });
        }
    }

    // Inicialización inicial
    initSelect2(".select2");

    // Limpiar borde rojo al cambiar Select2 (Usamos delegación para clones)
    $(document).on("change", ".select2", function () {
        $(this)
            .next(".select2-container")
            .find(".select2-selection")
            .css("border", "1px solid #ced4da");
    });

    const form = document.getElementById("formularioMadurez");
    if (!form) return;

    let pasoActual = 1;
    const btnSiguiente = document.getElementById("btnSiguiente");
    const btnAnterior = document.getElementById("btnAnterior");
    const btnSubmit = document.getElementById("btnSubmit");
    const btnDuplicar = document.getElementById("btnDuplicar");
    const progressBar = document.getElementById("progressBar");
    const progresoTexto = document.getElementById("progresoTexto");

    /*=============================================
    2. LIMPIEZA DINÁMICA DE ERRORES
    =============================================*/
    form.addEventListener("input", function (event) {
        if (
            event.target.tagName === "TEXTAREA" ||
            event.target.tagName === "INPUT"
        ) {
            event.target.classList.remove("is-invalid");
        }
    });

    form.addEventListener("change", function (event) {
        if (event.target.type === "radio") {
            const nameAttr = event.target.name;
            const match = nameAttr.match(/\[([^\]]+)\]/);
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

    /*=============================================
    FASE 2: LOGICA CHECKBOX NO RESPONDE
    =============================================*/
    $(document).on("change", ".chk-no-responde", function () {
        const paginaEncuesta = $(this).closest(".pagina-encuesta");
        const areaReasignar = paginaEncuesta.find(".area-reasignar");
        const bloquePrincipal = paginaEncuesta.find(
            ".bloque-respuestas-principal",
        );

        if ($(this).is(":checked")) {
            areaReasignar.slideDown();
            bloquePrincipal.fadeOut();
            bloquePrincipal
                .find("[required]")
                .prop("required", false)
                .removeClass("is-invalid");
            areaReasignar.find("input, textarea").prop("required", true);
        } else {
            areaReasignar.slideUp();
            bloquePrincipal.fadeIn();
            bloquePrincipal
                .find(".textarea-requerido, .radio-requerido")
                .prop("required", true);
            areaReasignar
                .find("input, textarea")
                .prop("required", false)
                .val("");
        }
    });

    /*=============================================
    FASE 3: DUPLICAR PREGUNTA (LIMPIEZA ATÓMICA)
    =============================================*/
    if (btnDuplicar) {
        btnDuplicar.addEventListener("click", function () {
            const paginas = document.querySelectorAll(".pagina-encuesta");
            const actual = Array.from(paginas).find(
                (p) => !p.classList.contains("d-none"),
            );

            if (!actual) return;

            // 1. CAPTURA DE VALORES ORIGINALES
            const valorDominio = $(actual).find(".select-dominio").val();
            const valorServicio = $(actual).find(".select-servicio").val();

            // 2. CLONACIÓN
            const clon = actual.cloneNode(true);
            const idOriginal = actual.getAttribute("data-id-original");
            const nuevoId = idOriginal + "_copy_" + Date.now();

            clon.id = "paso-clon-" + nuevoId;
            clon.classList.add("d-none");
            clon.setAttribute("data-id-original", nuevoId);

            // 3. LIMPIEZA PROFUNDA DE SELECT2 EN EL CLON
            // Eliminamos el contenedor visual que copió el cloneNode
            $(clon).find(".select2-container").remove();

            const selectsClon = $(clon).find(".select2");
            selectsClon.each(function () {
                // Truco Senior: Eliminamos los IDs internos de las opciones y del select
                $(this)
                    .removeClass("select2-hidden-accessible")
                    .removeAttr("data-select2-id")
                    .removeAttr("aria-hidden");

                $(this).find("option").removeAttr("data-select2-id");

                // Limpiar el rastro de jQuery Data para que Select2 crea que es nuevo
                $(this).removeData("select2");
                $(this).val(null);
            });

            // 4. AJUSTE DE NOMBRES E IDS
            $(clon)
                .find("input, textarea, select")
                .each(function () {
                    if (this.name)
                        this.name = this.name.replace(
                            `[${idOriginal}]`,
                            `[${nuevoId}]`,
                        );
                    if (this.id) this.id = this.id.replace(idOriginal, nuevoId);

                    if (this.type !== "hidden" && !$(this).hasClass("select2"))
                        this.value = "";
                    if (this.type === "radio" || this.type === "checkbox")
                        this.checked = false;
                });

            $(clon)
                .find("label")
                .each(function () {
                    const forAttr = $(this).attr("for");
                    if (forAttr)
                        $(this).attr(
                            "for",
                            forAttr.replace(idOriginal, nuevoId),
                        );
                });

            // 5. INSERTAR E INICIALIZAR
            actual.after(clon);

            // Inicializamos el Select2 del clon (ahora sí está limpio)
            initSelect2($(clon).find(".select2"));

            // 6. RE-APLICAR VALORES (Si se desea heredar)
            if (valorDominio)
                $(clon)
                    .find(".select-dominio")
                    .val(valorDominio)
                    .trigger("change");
            if (valorServicio)
                $(clon)
                    .find(".select-servicio")
                    .val(valorServicio)
                    .trigger("change");

            Swal.fire({
                icon: "success",
                title: "Pregunta Duplicada",
                text: "Copia funcional creada.",
                timer: 1500,
                showConfirmButton: false,
            });

            actualizarInterfaz();
        });
    }

    /*=============================================
    4. NAVEGACIÓN Y VALIDACIÓN
    =============================================*/
    function validarPasoActual() {
        const paginas = document.querySelectorAll(".pagina-encuesta");
        const visible = Array.from(paginas).find(
            (p) => !p.classList.contains("d-none"),
        );
        let esValido = true;
        let mensaje = "";

        const skip = visible.querySelector(".chk-no-responde");
        if (skip && skip.checked) {
            visible
                .querySelectorAll(".area-reasignar [required]")
                .forEach((el) => {
                    if (el.value.trim() === "") {
                        esValido = false;
                        el.classList.add("is-invalid");
                        mensaje =
                            "Los campos de reasignación son obligatorios.";
                    }
                });
        } else {
            visible.querySelectorAll("[required]").forEach((el) => {
                if (el.tagName === "SELECT") {
                    if (!$(el).val() || $(el).val().length === 0) {
                        esValido = false;
                        mensaje = "Selecciona Dominio y Servicio.";
                        $(el)
                            .next()
                            .find(".select2-selection")
                            .css("border", "1px solid #dc3545");
                    }
                } else if (el.type === "radio") {
                    const name = el.name;
                    if (
                        !visible.querySelector(`input[name="${name}"]:checked`)
                    ) {
                        esValido = false;
                        mensaje = "Selecciona una opción de respuesta.";
                        visible.querySelector(".feedback-radio").style.display =
                            "block";
                    }
                } else if (el.value.trim() === "") {
                    esValido = false;
                    el.classList.add("is-invalid");
                    mensaje = "La respuesta detallada es obligatoria.";
                }
            });
        }

        if (!esValido) Swal.fire("Atención", mensaje, "warning");
        return esValido;
    }

    function actualizarInterfaz() {
        const paginas = document.querySelectorAll(".pagina-encuesta");
        const total = paginas.length;
        let index = Array.from(paginas).findIndex(
            (p) => !p.classList.contains("d-none"),
        );
        let actualNum = index + 1;

        if (progressBar)
            progressBar.style.width = (actualNum / total) * 100 + "%";
        if (progresoTexto)
            progresoTexto.innerText = `Paso ${actualNum} de ${total}`;

        btnAnterior.classList.toggle("d-none", actualNum === 1);
        if (actualNum === total) {
            btnSiguiente.classList.add("d-none");
            btnSubmit.classList.remove("d-none");
        } else {
            btnSiguiente.classList.remove("d-none");
            btnSubmit.classList.add("d-none");
        }
    }

    btnSiguiente.addEventListener("click", () => {
        if (validarPasoActual()) {
            const paginas = document.querySelectorAll(".pagina-encuesta");
            let index = Array.from(paginas).findIndex(
                (p) => !p.classList.contains("d-none"),
            );
            paginas[index].classList.add("d-none");
            paginas[index + 1].classList.remove("d-none");
            actualizarInterfaz();
            window.scrollTo({ top: 0, behavior: "smooth" });
        }
    });

    btnAnterior.addEventListener("click", () => {
        const paginas = document.querySelectorAll(".pagina-encuesta");
        let index = Array.from(paginas).findIndex(
            (p) => !p.classList.contains("d-none"),
        );
        if (index > 0) {
            paginas[index].classList.add("d-none");
            paginas[index - 1].classList.remove("d-none");
            actualizarInterfaz();
        }
    });

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        if (validarPasoActual()) {
            const datos = new FormData(form);
            $.ajax({
                url: "app/ajax/enviaFormulario.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function (r) {
                    if (r.trim() === "ok") {
                        Swal.fire(
                            "¡Éxito!",
                            "Evaluación guardada.",
                            "success",
                        ).then(() => (window.location = "salir"));
                    }
                },
            });
        }
    });

    actualizarInterfaz();
});
