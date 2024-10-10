<?php
require_once "../modelos/Asistencia.php";
if (strlen(session_id()) < 1)
	session_start();

$asistencia = new Asistencia();

$codigo_persona = isset($_POST["codigo_persona"]) ? limpiarCadena($_POST["codigo_persona"]) : "";
$iddepartamento = isset($_POST["iddepartamento"]) ? limpiarCadena($_POST["iddepartamento"]) : "";
$idasistencia = isset($_POST["idasistencia"]) ? limpiarCadena($_POST["idasistencia"]) : ""; // Nuevo campo idasistencia para edición

switch ($_GET["op"]) {

	// Caso para insertar o editar el registro
	case 'guardaryeditar':
		$idasistencia = isset($_POST["idasistencia"]) ? limpiarCadena($_POST["idasistencia"]) : "";
		$codigo_persona = limpiarCadena($_POST["codigo_persona"]);
		$fecha_hora = limpiarCadena($_POST["fecha_hora"]);
		$tipo = limpiarCadena($_POST["tipo"]);
		$motivo_modificacion = isset($_POST["motivo_modificacion"]) ? limpiarCadena($_POST["motivo_modificacion"]) : "";

		if (empty($idasistencia)) {
			// Insertar nuevo registro
			$rspta = $asistencia->insertar($codigo_persona, $fecha_hora, $tipo);
			echo $rspta ? "Registro guardado" : "No se pudo registrar";
		} else {
			// Editar registro existente
			$rspta = $asistencia->editar($idasistencia, $codigo_persona, $fecha_hora, $tipo);

			// Registrar la modificación en la tabla de auditoría
			if ($rspta) {
				$sql_auditoria = "INSERT INTO auditoria_asistencia (idasistencia, codigo_persona, fecha_hora, tipo, motivo, fecha_modificacion)
                                  VALUES ('$idasistencia', '$codigo_persona', '$fecha_hora', '$tipo', '$motivo_modificacion', NOW())";
				ejecutarConsulta($sql_auditoria);
				echo "Registro actualizado y auditoría guardada";
			} else {
				echo "No se pudo actualizar";
			}
		}
		break;

	// Caso para obtener un registro específico para editar
	case 'mostrar':
		$rspta = $asistencia->mostrar($idasistencia);
		echo json_encode($rspta);
		break;

	// LISTA DE ASISTENCIA ADMINISTRADOR
	case 'listar':
		$rspta = $asistencia->listar();
		$data = array();

		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->idasistencia . ')"><i class="fa fa-pencil"></i></button>' .
					' <button class="btn btn-info btn-xs" onclick="ver(' . $reg->idasistencia . ')"><i class="fa fa-eye"></i></button>',
				"1" => $reg->codigo_persona,
				"2" => $reg->nombre . ' ' . $reg->apellidos,
				"3" => $reg->departamento,
				"4" => $reg->fecha_hora,
				"5" => $reg->tipo,
				"6" => $reg->fecha
			);
		}

		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data),
			"iTotalDisplayRecords" => count($data),
			"aaData" => $data
		);
		echo json_encode($results);
		break;
	// LISTA DE ASISTENCIA PERSONAL
	case 'listaru':
		$idusuario = $_SESSION["idusuario"];
		$rspta = $asistencia->listaru($idusuario);
		//declaramos un array
		$data = array();


		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => '<button class="btn btn-success btn-xs">Registrado <i class="fa fa-check"></i></button>',
				"1" => $reg->codigo_persona,
				"2" => $reg->nombre . ' ' . $reg->apellidos,
				"3" => $reg->departamento,
				"4" => $reg->fecha_hora,
				"5" => $reg->tipo,
				"6" => $reg->fecha
			);
		}

		$results = array(
			"sEcho" => 1,//info para datatables
			"iTotalRecords" => count($data),//enviamos el total de registros al datatable
			"iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);

		break;

	case 'listar_asistencia':
		$fecha_inicio = $_REQUEST["fecha_inicio"];
		$fecha_fin = $_REQUEST["fecha_fin"];
		$codigo_persona = $_REQUEST["idcliente"];
		$rspta = $asistencia->listar_asistencia($fecha_inicio, $fecha_fin, $codigo_persona);
		//declaramos un array
		$data = array();


		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => $reg->fecha,
				"1" => $reg->nombre . ' ' . $reg->apellidos,
				"2" => $reg->tipo,
				"3" => $reg->fecha_hora,
				"4" => $reg->codigo_persona
			);
		}

		$results = array(
			"sEcho" => 1,//info para datatables
			"iTotalRecords" => count($data),//enviamos el total de registros al datatable
			"iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);

		break;
	case 'listar_asistenciau':
		$fecha_inicio = isset($_REQUEST["fecha_inicio"]) ? limpiarCadena($_REQUEST["fecha_inicio"]) : null;
		$fecha_fin = isset($_REQUEST["fecha_fin"]) ? limpiarCadena($_REQUEST["fecha_fin"]) : null;
		$codigo_persona = $_SESSION["codigo_persona"];

		if ($fecha_inicio && $fecha_fin && $codigo_persona) {
			$rspta = $asistencia->listar_asistencia($fecha_inicio, $fecha_fin, $codigo_persona);
			$data = array();

			while ($reg = $rspta->fetch_object()) {
				// Formatea la fecha en formato DD/MM/AAAA
				$formattedDate = date("d/m/Y", strtotime($reg->fecha));
				$formattedDateTime = date("d/m/Y H:i:s", strtotime($reg->fecha_hora));

				$data[] = array(
					"0" => $formattedDate, // Fecha en formato DD/MM/AAAA
					"1" => $reg->nombre . ' ' . $reg->apellidos,
					"2" => $reg->tipo,
					"3" => $formattedDateTime, // Fecha y hora en formato DD/MM/AAAA HH:mm:ss
					"4" => $reg->codigo_persona
				);
			}

			$results = array(
				"sEcho" => 1,
				"iTotalRecords" => count($data),
				"iTotalDisplayRecords" => count($data),
				"aaData" => $data
			);
			echo json_encode($results);
		} else {
			echo json_encode(["error" => "Parámetros inválidos"]);
		}
		break;


	case 'selectPersona':
		require_once "../modelos/Usuario.php";
		$usuario = new Usuario();

		$rspta = $usuario->listar();

		while ($reg = $rspta->fetch_object()) {
			print_r($reg); // Esto te permitirá ver si los datos están siendo obtenidos correctamente
			echo '<option value=' . $reg->codigo_persona . '>' . $reg->nombre . ' ' . $reg->apellidos . '</option>';
		}
		break;

		case 'ver_detalle':
			$idasistencia = isset($_POST["idasistencia"]) ? limpiarCadena($_POST["idasistencia"]) : "";
		
			// Consulta para obtener la información del marcado inicial
			$sql = "SELECT a.idasistencia, a.codigo_persona, a.fecha_hora, a.tipo, u.nombre, u.apellidos
					FROM asistencia a
					INNER JOIN usuarios u ON a.codigo_persona = u.codigo_persona
					WHERE a.idasistencia = '$idasistencia'";
			$rspta_asistencia = ejecutarConsultaSimpleFila($sql);
		
			// Consulta para obtener todas las modificaciones desde la auditoría
			$sql_auditoria = "SELECT idauditoria, idasistencia, fecha_hora, tipo, motivo, fecha_modificacion
							  FROM auditoria_asistencia
							  WHERE idasistencia = '$idasistencia'
							  ORDER BY fecha_modificacion DESC";
			$rspta_auditoria = ejecutarConsulta($sql_auditoria);
		
			// Formamos un array con los resultados
			$auditorias = array();
			while ($row = $rspta_auditoria->fetch_object()) {
				$auditorias[] = $row;
			}
		
			// Devolvemos los detalles de la asistencia y el historial de modificaciones
			echo json_encode(array("asistencia" => $rspta_asistencia, "auditorias" => $auditorias));
			break;
		


}

?>