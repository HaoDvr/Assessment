/*-- --------------------------
* FUNCION PARA VER/OCULTAR PASSWORD EN FORMULARIO USUAIRO
-------------------------------*/
function mostrarPassword() {
  var x = document.getElementById("nuevaContrasena");
  var icono = document.getElementById("iconoPassword");
  var boton = icono.closest("button"); // Seleccionamos el botón que envuelve al icono

  if (x.type === "password") {
    x.type = "text";
    icono.classList.replace("fa-eye", "fa-eye-slash");
    // Opcional: Cambiar el color del botón cuando está activo
    boton.classList.replace("btn-primary", "btn-info");
  } else {
    x.type = "password";
    icono.classList.replace("fa-eye-slash", "fa-eye");
    boton.classList.replace("btn-info", "btn-primary");
  }
}
