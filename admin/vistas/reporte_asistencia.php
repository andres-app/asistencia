<?php
// Iniciar el "output buffering" para prevenir que los warnings interfieran con el PDF
ob_start();

// Incluir la biblioteca FPDF
require '../fpdf/fpdf.php';

// Incluir la conexión a la base de datos
require_once "../config/Conexion.php";

// Recibir los parámetros desde el formulario
$fecha_inicio = $_GET["fecha_inicio"];
$fecha_fin = $_GET["fecha_fin"];
$idcliente = $_GET["idcliente"];

// Variables para el resumen
$minutos_laborados = 0;
$minutos_esperados = 0; // Total de minutos esperados basado en días laborables
$minutos_totales_esperados = 0; // Minutos totales que se espera que el empleado trabaje
$minutos_no_marcados = 0; // Inicializamos la variable para evitar errores

// Crear el PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 20);

// Función para obtener el mes en letras
function obtenerMesEnLetras($fecha) {
    setlocale(LC_TIME, 'spanish'); // Usar la localización "spanish" para Windows
    return utf8_decode(strftime('%B', strtotime($fecha))); // Obtiene el mes en letras y lo decodifica
}

// Función para obtener todas las fechas en un rango
function generarFechas($fecha_inicio, $fecha_fin) {
    $fechas = [];
    $current = strtotime($fecha_inicio);
    $end = strtotime($fecha_fin);

    while ($current <= $end) {
        $fechas[] = date('Y-m-d', $current);
        $current = strtotime('+1 day', $current);
    }

    return $fechas;
}

// Función para obtener el nombre del día de la semana en español sin tildes
function obtenerDiaSemanaSinTildes($fecha) {
    setlocale(LC_TIME, 'spanish'); // Usar la localización "spanish" para Windows
    $dia_semana = strftime('%A', strtotime($fecha)); // Obtiene el día de la semana en letras

    // Reemplazar manualmente los días con tildes por versiones sin tildes
    $dia_semana = str_replace(
        ['lunes', 'martes', 'Miércoles', 'jueves', 'viernes', 'sábado', 'domingo'],
        ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
        strtolower($dia_semana)
    );

    return ucfirst($dia_semana); // Convertir la primera letra en mayúscula
}

// Función para calcular la diferencia en minutos entre dos horas
function calcularDiferenciaMinutos($hora_inicio, $hora_fin) {
    $inicio = strtotime($hora_inicio);
    $fin = strtotime($hora_fin);
    return ($fin - $inicio) / 60; // Convertimos la diferencia en segundos a minutos
}

// Encabezado del PDF
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Reporte de Asistencia'), 0, 1, 'C');
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, utf8_decode('QUIMBAYA TOURS'), 0, 1, 'C');

// Obtener el mes y el año de la fecha de inicio y de fin
$mes_inicio = obtenerMesEnLetras($fecha_inicio);
$mes_fin = obtenerMesEnLetras($fecha_fin);
$anio_inicio = date('Y', strtotime($fecha_inicio));
$anio_fin = date('Y', strtotime($fecha_fin));

// Mostrar el rango de meses en caso de que sean distintos
if ($mes_inicio == $mes_fin && $anio_inicio == $anio_fin) {
    $periodo = "Periodo: $mes_inicio del $anio_inicio";
} else {
    if ($anio_inicio == $anio_fin) {
        $periodo = "Periodo: $mes_inicio, $mes_fin del $anio_inicio";
    } else {
        $periodo = "Periodo: $mes_inicio del $anio_inicio a $mes_fin del $anio_fin";
    }
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode($periodo), 0, 1, 'C'); // Mostramos el periodo con los meses en letras

// Espacio
$pdf->Ln(10);

// Estilo de la tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(200, 220, 255); // Color de fondo de las celdas de la cabecera

// Títulos de las columnas
$pdf->Cell(25, 10, 'Dia', 1, 0, 'C', true); // Columna de día
$pdf->Cell(25, 10, 'Fecha', 1, 0, 'C', true); // Columna de fecha
$pdf->Cell(50, 10, 'Empleado', 1, 0, 'C', true);
$pdf->Cell(25, 10, 'Registro', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Hora', 1, 0, 'C', true); // Cambiado el título a "Hora"
$pdf->Cell(30, 10, 'Codigo', 1, 1, 'C', true);

// Generar todas las fechas del rango
$fechas = generarFechas($fecha_inicio, $fecha_fin);

// Consultar los registros de la base de datos para el cliente y las fechas seleccionadas
$sql = "SELECT a.fecha, u.nombre, u.apellidos, a.tipo, a.fecha_hora, a.codigo_persona 
        FROM asistencia a 
        INNER JOIN usuarios u ON a.codigo_persona = u.codigo_persona 
        WHERE a.fecha >= '$fecha_inicio' AND a.fecha <= '$fecha_fin'";

if (!empty($idcliente)) {
    $sql .= " AND a.codigo_persona = '$idcliente'";
}

$resultado = ejecutarConsulta($sql);

// Almacenar los resultados en un array para verificarlos más fácilmente
$registros = [];
while ($row = $resultado->fetch_assoc()) {
    $registros[$row['fecha']][] = $row;
}

// Agregar los registros a la tabla en el PDF
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(240, 240, 240); // Color de fondo para las filas
$fill = false;

// Variables para calcular las horas
$entrada = '';
$salida = '';

// Recorrer todas las fechas y verificar si tienen registros
foreach ($fechas as $fecha) {
    $dia_semana = obtenerDiaSemanaSinTildes($fecha); // Obtener el día de la semana sin tildes

    // Calcular minutos esperados si es día laboral (lunes a viernes)
    if (!in_array($dia_semana, ['Sabado', 'Domingo'])) {
        $minutos_esperados = 600; // 10 horas = 600 minutos por día laboral
        $minutos_totales_esperados += $minutos_esperados;
    }

    if (isset($registros[$fecha])) {
        $entrada = '';
        $salida = '';

        foreach ($registros[$fecha] as $registro) {
            $fecha_formateada = date('d-m-Y', strtotime($fecha));
            $hora = date('H:i:s', strtotime($registro['fecha_hora'])); // Extraer solo la hora

            // Mostrar el registro
            $pdf->Cell(25, 10, utf8_decode(ucfirst($dia_semana)), 1, 0, 'C', $fill); // Día de la semana
            $pdf->Cell(25, 10, utf8_decode($fecha_formateada), 1, 0, 'C', $fill); // Fecha en formato DD-MM-YYYY
            $pdf->Cell(50, 10, utf8_decode($registro['nombre'] . ' ' . $registro['apellidos']), 1, 0, 'L', $fill);
            $pdf->Cell(25, 10, utf8_decode($registro['tipo']), 1, 0, 'C', $fill);
            $pdf->Cell(30, 10, utf8_decode($hora), 1, 0, 'C', $fill); // Mostrar solo la hora
            $pdf->Cell(30, 10, utf8_decode($registro['codigo_persona']), 1, 1, 'C', $fill);
            $fill = !$fill; // Alternar color de fondo para cada fila

            // Verificar si es entrada o salida
            if ($registro['tipo'] == 'Entrada') {
                $entrada = $hora;
            } elseif ($registro['tipo'] == 'Salida') {
                $salida = $hora;
            }
        }

        // Si hay tanto entrada como salida, calcular las horas laboradas
        if ($entrada && $salida) {
            $minutos_laborados += calcularDiferenciaMinutos($entrada, $salida);
        }
    } else {
        // Si no hay registro para esta fecha, mostrar "No registro"
        $fecha_formateada = date('d-m-Y', strtotime($fecha));
        $pdf->Cell(25, 10, utf8_decode(ucfirst($dia_semana)), 1, 0, 'C', $fill); // Día de la semana
        $pdf->Cell(25, 10, utf8_decode($fecha_formateada), 1, 0, 'C', $fill); // Fecha en formato DD-MM-YYYY
        $pdf->Cell(50, 10, 'No registro', 1, 0, 'C', $fill); // Empleado "No registro"
        $pdf->Cell(25, 10, '-', 1, 0, 'C', $fill); // Tipo vacío
        $pdf->Cell(30, 10, '-', 1, 0, 'C', $fill); // Hora vacía
        $pdf->Cell(30, 10, '-', 1, 1, 'C', $fill);

        // Aumentar los minutos no marcados en un día completo (10 horas = 600 minutos)
        if (!in_array($dia_semana, ['Sabado', 'Domingo'])) {
            $minutos_no_marcados += 600;
        }
    }

    $fill = !$fill;
}

// Calcular horas y minutos laborados
$horas_laboradas = floor($minutos_laborados / 60);
$minutos_restantes_laborados = $minutos_laborados % 60;

// Calcular horas y minutos no marcadas como la diferencia entre las horas esperadas y las laboradas
$minutos_no_marcados = $minutos_totales_esperados - $minutos_laborados;
$horas_no_marcadas = floor($minutos_no_marcados / 60);
$minutos_restantes_no_marcados = $minutos_no_marcados % 60;

// Pie de página
$pdf->Ln(10); // Espacio antes del resumen
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Resumen:', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, 'Total de horas esperadas: ' . floor($minutos_totales_esperados / 60) . 'h ' . ($minutos_totales_esperados % 60) . 'm', 0, 1, 'L');
$pdf->Cell(0, 10, 'Total de horas laboradas: ' . $horas_laboradas . 'h ' . $minutos_restantes_laborados . 'm', 0, 1, 'L');
$pdf->Cell(0, 10, 'Total de horas no marcadas: ' . $horas_no_marcadas . 'h ' . $minutos_restantes_no_marcados . 'm', 0, 1, 'L');

// Salida del archivo PDF
$pdf->Output();
ob_end_flush(); // Finalizar el "output buffering" para generar el PDF sin errores
?>
