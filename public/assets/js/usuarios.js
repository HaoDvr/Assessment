document.addEventListener("DOMContentLoaded", () => {
  // 1. Definimos el Toast dentro del DOMContentLoaded para asegurar que Swal ya existe
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
  });

  /*-- --------------------------
  * VALIDAMOS CORREO EXISTENTE
  -------------------------------*/
  const inputCorreo = document.querySelector('input[name="nuevoCorreo"]');

  if (inputCorreo) {
    inputCorreo.addEventListener("change", async (e) => {
      const correo = e.target.value;

      // Si el campo no está vacío, validamos
      if (correo != "") {
        const datos = new FormData();
        datos.append("validarCorreo", correo);

        try {
          const response = await fetch("app/ajax/usuarios.ajax.php", {
            method: "POST",
            body: datos,
          });

          const resultado = await response.json();

          if (resultado) {
            // Si el resultado es verdadero, el correo ya existe
            Toast.fire({
              icon: "warning",
              title: "Este correo ya está registrado, por favor usa otro.",
            });

            // Limpiamos el campo y le ponemos un borde rojo
            e.target.value = "";
            e.target.classList.add("is-invalid");
          } else {
            // Si no existe, quitamos el borde rojo
            e.target.classList.remove("is-invalid");
            e.target.classList.add("is-valid");
          }
        } catch (error) {
          console.error("Error al validar correo:", error);
        }
      }
    });
  }

  /*-- --------------------------
  * AGREGAR USUARIO
  -------------------------------*/
  const formUsuario = document.querySelector("#formAddUsuario");

  if (formUsuario) {
    formUsuario.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(e.target);

      try {
        let response = await fetch("app/ajax/usuarios.ajax.php", {
          method: "POST",
          body: formData,
        });

        if (!response.ok) {
          throw new Error("Error en la comunicación con el servidor");
        }

        // Aquí convertimos la respuesta a JSON
        const resultado = await response.json();

        if (resultado === "ok") {
          // Usamos el Toast que definimos arriba
          Toast.fire({
            icon: "success",
            title: "¡Usuario guardado correctamente!",
          }).then(() => {
            window.location.href = "usuarios";
          });
        } else {
          console.error("Respuesta inesperada:", resultado);
        }
      } catch (error) {
        console.error("Error detallado:", error);
        // Si algo falla, el Toast también funcionará aquí
        Toast.fire({
          icon: "error",
          title: "Hubo un error al procesar el registro",
        });
      }
    });
  }

  /*-- --------------------------
  * ELIMINAR USUARIO
  -------------------------------*/
  // public/assets/js/usuarios.js

  document.addEventListener("click", async (e) => {
    // Verificamos si se hizo clic en el botón de eliminar o su icono
    const btnEliminar = e.target.closest(".btnEliminarUsuario");

    if (btnEliminar) {
      const idUsuario = btnEliminar.getAttribute("idUsuario");

      // Alerta de confirmación profesional
      Swal.fire({
        title: "¿Estás seguro de eliminar este usuario?",
        text: "¡Esta acción no se puede deshacer!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      }).then(async (result) => {
        if (result.isConfirmed) {
          const datos = new FormData();
          datos.append("idEliminar", idUsuario);

          try {
            const response = await fetch("app/ajax/usuarios.ajax.php", {
              method: "POST",
              body: datos,
            });

            const resultado = await response.json();

            if (resultado == "ok") {
              Swal.fire(
                "¡Eliminado!",
                "El usuario ha sido borrado.",
                "success"
              ).then(() => {
                window.location.href = "usuarios";
              });
            }
          } catch (error) {
            console.error("Error al eliminar:", error);
          }
        }
      });
    }
  });
});
