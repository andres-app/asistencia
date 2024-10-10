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
    }).fail(function(e) {
        console.log("Error al cargar empleados: ", e.responseText); // Mostrar error en consola si la solicitud falla
    });
    
}

// Función para limpiar el formulario
function limpiar() {
    $("#idasistencia").val("");
    $("#nombre").val("");
    $("#codigo_persona").val("");
    $("#fecha_hora").val("");
    $("#tipo").val("Entrada");
}

// Función para mostrar el formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
    }
}

// Función para cancelar el formulario
function cancelarform() {
    limpiar();
    mostrarform(false);
}

// Función para listar los registros en el datatable
function listar() {
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true, // Activamos el procesamiento del datatable
        "aServerSide": true, // Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', // Definimos los elementos del control de tabla
        buttons: [
            'excelHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/asistencia.php?op=listar',
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

// Función para listar registros de asistencia para usuarios con menos privilegios
function listaru() {
    tabla = $('#tbllistadou').dataTable({
        "aProcessing": true, // Activar procesamiento del datatable
        "aServerSide": true, // Paginación y filtrado del lado del servidor
        dom: 'Bfrtip', // Definir los elementos del control de la tabla
        buttons: ['excelHtml5', 'pdf'],
        "ajax": {
            url: '../ajax/asistencia.php?op=listaru',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true, // Destruir el datatable existente antes de recargar
        "iDisplayLength": 10, // Paginación
        "order": [[0, "desc"]] // Ordenar por la primera columna en orden descendente
    }).DataTable();
}

// Función para guardar y editar
function guardaryeditar(e) {
    e.preventDefault(); // Evitamos que se recargue la página
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/asistencia.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            alert(datos); // Mostramos el mensaje del backend
            mostrarform(false); // Ocultamos el formulario
            tabla.ajax.reload(); // Recargamos la tabla
        }
    });
}

// Función para mostrar un registro en el formulario para editar
function mostrar(idasistencia) {
    $.post("../ajax/asistencia.php?op=mostrar", { idasistencia: idasistencia }, function (data, status) {
        data = JSON.parse(data); // Parseamos la respuesta JSON
        mostrarform(true); // Mostramos el formulario

        $("#idasistencia").val(data.idasistencia);
        $("#nombre").val(data.nombre);
        $("#codigo_persona").val(data.codigo_persona);
        $("#fecha_hora").val(data.fecha_hora);
        $("#tipo").val(data.tipo);
    });
}

function listar_asistencia() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var idcliente = $("#idcliente").val();

    tabla = $('#tbllistado_asistencia').dataTable({
        "aProcessing": true, // Activamos el procedimiento del datatable
        "aServerSide": true, // Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', // Definimos los elementos del control de la tabla
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
        "iDisplayLength": 10, // Paginación
        "order": [[0, "desc"]], // Ordenar (columna, orden)
        "columnDefs": [
            {
                "targets": 0, // Primera columna donde está la fecha
                "render": function (data, type, row) {
                    if (data) {
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

function ver(idasistencia) {
    $.post("../ajax/asistencia.php?op=ver_detalle", { idasistencia: idasistencia }, function(data, status) {
        data = JSON.parse(data);  // Convertimos la respuesta JSON en un objeto

        // Mostramos los detalles en la sección correspondiente
        var asistencia = data.asistencia;
        var auditorias = data.auditorias;

        // Llenamos los campos con los detalles del registro de asistencia
        $("#idasistencia_detalle").val(asistencia.idasistencia);
        $("#codigo_persona_detalle").val(asistencia.codigo_persona);
        $("#nombre_detalle").val(asistencia.nombre + " " + asistencia.apellidos);
        $("#tipo_detalle").val(asistencia.tipo);
        $("#fecha_hora_detalle").val(asistencia.fecha_hora);

        // Generamos el timeline dinámicamente
        var timeline = '';

        // Recorrer todas las modificaciones (auditorías) para agregar al timeline
        auditorias.forEach(function(auditoria) {
            timeline += `
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">Fecha Modificación: ${auditoria.fecha_modificacion}</div>
                        Fecha/Hora Modificada: ${auditoria.fecha_hora}<br>
                        Tipo de Asistencia: ${auditoria.tipo}<br>
                        Motivo: ${auditoria.motivo}<br>
                        <strong>Modificado por:</strong> ${auditoria.usuario_modificacion}
                    </div>
                </li>
            `;
        });

        // Insertamos el timeline generado en el contenedor
        $("#timeline_detalle").html(timeline);

        // Mostramos el formulario de detalles y ocultamos el listado de registros
        $("#detalleasistencia").show();
        $("#listadoregistros").hide();
    });
}







// Función para ocultar la sección de detalles y volver al listado
function cancelarDetalle() {
    $("#detalleasistencia").hide();
    $("#listadoregistros").show();
}



function listar_asistenciau() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();

    if (!fecha_inicio || !fecha_fin) {
        alert("Por favor, selecciona ambas fechas.");
        return;
    }

    tabla = $('#tbllistado_asistenciau').dataTable({
        "aProcessing": true, // Activamos el procesamiento del datatable
        "aServerSide": true, // Realizamos la paginación y filtrado en el servidor
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

function generarPDF() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var idcliente = $("#idcliente").val();

    // Ajusta la ruta para acceder correctamente al archivo reporte_asistencia.php
    window.open('../vistas/reporte_asistencia.php?fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin + '&idcliente=' + idcliente, '_blank');
}



$(document).ready(function () {
    // Al hacer clic en el botón Guardar
    $("#btnGuardar").click(function (e) {
        e.preventDefault(); // Evita el envío inmediato del formulario
        $("#modalConfirmar").modal("show"); // Muestra el modal de confirmación
    });

    // Confirmación para guardar la modificación
    $("#confirmarGuardar").click(function () {
        var motivo = $("#motivo").val();
        if (motivo.trim() === "") {
            alert("Por favor, ingresa el motivo de la modificación.");
        } else {
            // Agregar el motivo al formulario como un campo oculto
            $("<input>").attr({
                type: "hidden",
                id: "motivo_modificacion",
                name: "motivo_modificacion",
                value: motivo
            }).appendTo("#formulario");

            // Enviar el formulario con AJAX
            var formData = new FormData($("#formulario")[0]);

            $.ajax({
                url: "../ajax/asistencia.php?op=guardaryeditar",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (datos) {
                    alert(datos); // Mostrar el mensaje del backend
                    $("#modalConfirmar").modal("hide"); // Cierra el modal
                    $("#formulario")[0].reset(); // Limpiar el formulario
                    $("#formularioregistros").hide(); // Ocultar el formulario de nuevo
                    $("#listadoregistros").show(); // Mostrar el listado de registros
                    tabla.ajax.reload(); // Recargar el datatable
                }
            });
        }
    });
});



init();
