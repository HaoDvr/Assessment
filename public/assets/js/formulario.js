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

    initSelect2(".select2");

    $(document).on("change", ".select2", function () {
        $(this)
            .next(".select2-container")
            .find(".select2-selection")
            .css("border", "1px solid #ced4da");
    });

    const form = document.getElementById("formularioMadurez");
    if (!form) return;

    const btnSiguiente = document.getElementById("btnSiguiente");
    const btnAnterior = document.getElementById("btnAnterior");
    const btnSubmit = document.getElementById("btnSubmit");
    const btnDuplicar = document.getElementById("btnDuplicar");
    const progressBar = document.getElementById("progressBar");
    const progresoTexto = document.getElementById("progresoTexto");

    /*=============================================
    2. LIMPIEZA DINÁMICA DE ERRORES Y CAPTURA DE RADIOS
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

                // CAPTURA INTELIGENTE:
                // Solo actualizamos si el radio tiene los atributos de la respuesta principal.
                // Esto evita que los radios de "Sub-niveles" limpien el valor ya guardado.
                const nuevoTexto = event.target.getAttribute("data-texto");
                const nuevoValor = event.target.getAttribute("data-valor");

                if (nuevoTexto !== null && nuevoValor !== null) {
                    if (txtInput) txtInput.value = nuevoTexto;
                    if (valInput) valInput.value = nuevoValor;
                }
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

            const valorDominio = $(actual).find(".select-dominio").val();
            const valorServicio = $(actual).find(".select-servicio").val();

            const clon = actual.cloneNode(true);
            const idOriginal = actual.getAttribute("data-id-original");
            const nuevoId = idOriginal + "_copy_" + Date.now();

            clon.id = "paso-clon-" + nuevoId;
            clon.classList.add("d-none");
            clon.setAttribute("data-id-original", nuevoId);

            $(clon).find(".select2-container").remove();
            const selectsClon = $(clon).find(".select2");
            selectsClon.each(function () {
                $(this)
                    .removeClass("select2-hidden-accessible")
                    .removeAttr("data-select2-id")
                    .removeAttr("aria-hidden");
                $(this).find("option").removeAttr("data-select2-id");
                $(this).removeData("select2");
                $(this).val(null);
            });

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

            actual.after(clon);
            initSelect2($(clon).find(".select2"));

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
                timer: 1500,
                showConfirmButton: false,
            });
            actualizarInterfaz();
        });
    }

    /*=============================================
    4. NAVEGACIÓN Y ESCÁNER DE VALIDACIÓN
    =============================================*/

    function validarFormulario(validarTodo = false) {
        const paginas = document.querySelectorAll(".pagina-encuesta");
        const paginasAProcesar = validarTodo
            ? Array.from(paginas)
            : [
                  Array.from(paginas).find(
                      (p) => !p.classList.contains("d-none"),
                  ),
              ];

        let todoValido = true;
        let primerPasoConError = null;

        paginasAProcesar.forEach((pagina, index) => {
            let errorEnEstaPagina = false;
            const skip = pagina.querySelector(".chk-no-responde");

            if (skip && skip.checked) {
                pagina
                    .querySelectorAll(".area-reasignar [required]")
                    .forEach((el) => {
                        if (el.value.trim() === "") {
                            todoValido = false;
                            errorEnEstaPagina = true;
                            el.classList.add("is-invalid");
                        }
                    });
            } else {
                pagina.querySelectorAll("[required]").forEach((el) => {
                    if (el.tagName === "SELECT") {
                        if (!$(el).val() || $(el).val().length === 0) {
                            todoValido = false;
                            errorEnEstaPagina = true;
                            $(el)
                                .next()
                                .find(".select2-selection")
                                .css("border", "1px solid #dc3545");
                        }
                    } else if (el.type === "radio") {
                        const name = el.name;
                        // Solo validamos si es el radio de la respuesta principal (no el sub-nivel opcional)
                        if (name.includes("[id_seleccionada]")) {
                            if (
                                !pagina.querySelector(
                                    `input[name="${name}"]:checked`,
                                )
                            ) {
                                todoValido = false;
                                errorEnEstaPagina = true;
                                pagina.querySelector(
                                    ".feedback-radio",
                                ).style.display = "block";
                            }
                        }
                    } else if (el.value.trim() === "") {
                        todoValido = false;
                        errorEnEstaPagina = true;
                        el.classList.add("is-invalid");
                    }
                });
            }

            if (errorEnEstaPagina && primerPasoConError === null) {
                primerPasoConError = index;
            }
        });

        if (!todoValido) {
            Swal.fire(
                "Atención",
                "Te faltan campos por contestar. Te regresaremos a la pregunta pendiente.",
                "warning",
            );

            if (validarTodo && primerPasoConError !== null) {
                paginas.forEach((p) => p.classList.add("d-none"));
                paginas[primerPasoConError].classList.remove("d-none");
                actualizarInterfaz();
            }
        }
        return todoValido;
    }

    btnSiguiente.addEventListener("click", () => {
        const paginas = document.querySelectorAll(".pagina-encuesta");
        let index = Array.from(paginas).findIndex(
            (p) => !p.classList.contains("d-none"),
        );
        if (index < paginas.length - 1) {
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

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        if (validarFormulario(true)) {
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
