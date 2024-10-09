document.getElementById('exportButton').addEventListener('click', function () {
    const table = document.getElementById('myTable');
    // Obtener los datos de la tabla, omitiendo la última columna
    const data = [];
    for (let i = 0; i < table.rows.length; i++) {
        const row = [];
        for (let j = 0; j < table.rows[i].cells.length - 1; j++) { // Omitir la última columna
            row.push(table.rows[i].cells[j].innerText);
        }
        data.push(row);
    }

    // Crear un nuevo libro de trabajo y hoja
    const ws = XLSX.utils.aoa_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Catálogo productos");
    XLSX.writeFile(wb, 'productos.xlsx');
});