$(function () {
  // Verificamos si la tabla existe en la página actual antes de inicializar
  if ($("#tablaUsuarios").length > 0) {
    $("#tablaUsuarios").DataTable({
      responsive: true,
      lengthChange: true,
      autoWidth: false,
      pageLength: 5,
      lengthChange: false,
      language: {
        decimal: "",
        emptyTable: "No hay datos disponibles en la tabla",
        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty: "Mostrando 0 a 0 de 0 registros",
        infoFiltered: "(filtrado de _MAX_ registros totales)",
        infoPostFix: "",
        thousands: ",",
        lengthMenu: "Mostrar _MENU_ registros",
        loadingRecords: "Cargando...",
        processing: "Procesando...",
        search: "Buscar:",
        zeroRecords: "No se encontraron registros coincidentes",
        paginate: {
          first: "Primero",
          last: "Último",
          next: "Siguiente",
          previous: "Anterior",
        },
        aria: {
          sortAscending: ": activar para ordenar la columna ascendente",
          sortDescending: ": activar para ordenar la columna descendente",
        },
      },
    });
  }
});
