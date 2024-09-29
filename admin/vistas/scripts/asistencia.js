var tabla;

// Función que se ejecuta al inicio
function init() {
    listar();
    listaru();

    // Manejo de formularios
    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });

    // Cargamos los items al select cliente
    $.post("../ajax/asistencia.php?op=selectPersona", function (r) {
        // Agregar opción "Todos" al inicio del select
        var todosOption = '<option value="">Todos</option>';
        $("#idcliente").html(todosOption + r); // Concatenar la opción "Todos" con los demás resultados
        $('#idcliente').selectpicker('refresh'); // Refrescar el selectpicker para actualizar
    });
}




//funcion listar
function listar() {
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true,//activamos el procedimiento del datatable
        "aServerSide": true,//paginacion y filrado realizados por el server
        dom: 'Bfrtip',//definimos los elementos del control de la tabla
        buttons: [
            'excelHtml5',
            'pdf'
        ],
        "ajax":
        {
            url: '../ajax/asistencia.php?op=listar',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,//paginacion
        "order": [[0, "desc"]]//ordenar (columna, orden)
    }).DataTable();
}
function listaru() {
    tabla = $('#tbllistadou').dataTable({
        "aProcessing": true,//activamos el procedimiento del datatable
        "aServerSide": true,//paginacion y filrado realizados por el server
        dom: 'Bfrtip',//definimos los elementos del control de la tabla
        buttons: [
            'excelHtml5',
            'pdf'
        ],
        "ajax":
        {
            url: '../ajax/asistencia.php?op=listaru',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,//paginacion
        "order": [[0, "desc"]]//ordenar (columna, orden)
    }).DataTable();
}



function listar_asistencia() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var idcliente = $("#idcliente").val();

    tabla = $('#tbllistado_asistencia').dataTable({
        "aProcessing": true, //activamos el procedimiento del datatable
        "aServerSide": true, //paginacion y filtrado realizados por el server
        dom: 'Bfrtip', //definimos los elementos del control de la tabla
        buttons: ['excelHtml5', 'pdf'],
        "ajax": {
            url: '../ajax/asistencia.php?op=listar_asistencia',
            data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idcliente: idcliente },
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, //paginacion
        "order": [[0, "desc"]], //ordenar (columna, orden)
        "columnDefs": [
            {
                "targets": 0, // Primera columna donde está la fecha
                "render": function (data, type, row) {
                    if (data) {
                        // Asumiendo que el formato que llega desde la base de datos es "YYYY-MM-DD"
                        var parts = data.split("-"); // Dividir la fecha en partes
                        var day = parts[2];  // Día
                        var month = parts[1];  // Mes
                        var year = parts[0];  // Año
                        return day + "/" + month + "/" + year; // Formato DD/MM/AAAA
                    }
                    return data; // Si no hay dato, lo muestra tal cual
                }

            },
            {
                "targets": 3, // Columna de Fecha/Hora
                "render": function (data, type, row) {
                    var date = new Date(data);
                    var day = ("0" + date.getDate()).slice(-2);
                    var month = ("0" + (date.getMonth() + 1)).slice(-2);
                    var year = date.getFullYear();
                    var hours = ("0" + date.getHours()).slice(-2);
                    var minutes = ("0" + date.getMinutes()).slice(-2);
                    var seconds = ("0" + date.getSeconds()).slice(-2);
                    return day + "/" + month + "/" + year + " " + hours + ":" + minutes + ":" + seconds; // Formato DD/MM/AAAA HH:mm:ss
                }
            }
        ]
    }).DataTable();
}


function listar_asistenciau() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();

    // Verificar si ambas fechas están definidas antes de continuar
    if (!fecha_inicio || !fecha_fin) {
        alert("Por favor, selecciona ambas fechas.");
        return;
    }

    tabla = $('#tbllistado_asistenciau').dataTable({
        "aProcessing": true, // Activamos el procesamiento del datatable
        "aServerSide": true, // Realizamos la paginación y el filtrado en el servidor
        dom: 'Bfrtip', // Definimos los elementos de la tabla
        buttons: ['excelHtml5', 'pdf'],
        "ajax": {
            url: '../ajax/asistencia.php?op=listar_asistenciau',
            data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, // Paginación
        "order": [[0, "desc"]] // Ordenar por la primera columna (Fecha) en orden descendente
    }).DataTable();
}


init();