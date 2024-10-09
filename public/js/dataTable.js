$(document).ready(function () {
    $('#myTable').DataTable({
        paging: false,
        language: {
            search: "Buscar producto:",
        },
        columnDefs: [
            { targets: [0,1, 3, 4], searchable: false }, // Desactiva la búsqueda para las columnas
        ]
    });
});