<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";

class Usuario {

    //implementamos nuestro constructor
    public function __construct() {}

    //metodo insertar registro
    public function insertar($nombre, $apellidos, $login, $iddepartamento, $idtipousuario, $email, $clavehash, $imagen, $usuariocreado, $codigo_persona) {
        date_default_timezone_set('America/Lima');
        $fechacreado = date('Y-m-d H:i:s');
        $sql = "INSERT INTO usuarios (nombre, apellidos, login, iddepartamento, idtipousuario, email, password, imagen, estado, fechacreado, usuariocreado, codigo_persona) 
                VALUES ('$nombre','$apellidos','$login','$iddepartamento','$idtipousuario','$email','$clavehash','$imagen','1','$fechacreado','$usuariocreado','$codigo_persona')";
        return ejecutarConsulta($sql);
    }

    public function editar($idusuario, $nombre, $apellidos, $login, $iddepartamento, $idtipousuario, $email, $imagen, $usuariocreado, $codigo_persona) {
        $sql = "UPDATE usuarios SET nombre='$nombre', apellidos='$apellidos', login='$login', iddepartamento='$iddepartamento', idtipousuario='$idtipousuario', email='$email', imagen='$imagen', usuariocreado='$usuariocreado', codigo_persona='$codigo_persona'
                WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    public function editar_clave($idusuario, $clavehash) {
        $sql = "UPDATE usuarios SET password='$clavehash' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    public function mostrar_clave($idusuario) {
        $sql = "SELECT idusuario, password FROM usuarios WHERE idusuario='$idusuario'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function desactivar($idusuario) {
        $sql = "UPDATE usuarios SET estado='0' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    public function activar($idusuario) {
        $sql = "UPDATE usuarios SET estado='1' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    //metodo para mostrar registros
    public function mostrar($idusuario) {
        $sql = "SELECT * FROM usuarios WHERE idusuario='$idusuario'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //listar registros
    public function listar() {
        $sql = "SELECT * FROM usuarios";
        return ejecutarConsulta($sql);
    }

    //cantidad total de usuarios
    public function cantidad_usuario() {
        $sql = "SELECT COUNT(*) as total_usuarios FROM usuarios";
        return ejecutarConsulta($sql);
    }

    // Obtener el total de asistencias del día actual
	public function total_asistencias_hoy() {
		$fecha_actual = date('Y-m-d'); // Obtenemos la fecha actual en formato YYYY-MM-DD
	
		// Consulta que obtiene solo la primera entrada del día por usuario
		$sql = "SELECT COUNT(DISTINCT codigo_persona) as total_asistencias 
				FROM asistencia 
				WHERE fecha = '$fecha_actual' 
				AND tipo = 'Entrada'";
	
		return ejecutarConsultaSimpleFila($sql); // Retorna el número de usuarios que han marcado su primera entrada hoy
	}
	

    // Obtener el total de empleados que no han marcado asistencia hoy
    public function empleados_sin_asistencia() {
        $fecha_actual = date('Y-m-d'); // Obtenemos la fecha actual en formato YYYY-MM-DD
        // Consulta para obtener los empleados que no han registrado asistencia en el día actual
        $sql = "SELECT COUNT(*) as empleados_sin_asistencia
                FROM usuarios u
                WHERE NOT EXISTS (
                    SELECT 1 FROM asistencia a WHERE a.codigo_persona = u.codigo_persona AND a.fecha = '$fecha_actual'
                ) AND u.estado = '1'"; // Filtramos por empleados activos
        return ejecutarConsultaSimpleFila($sql); // Retorna el número de empleados sin asistencia hoy
    }

	public function asistencias_por_dia() {
		$sql = "SELECT DATE(fecha) as fecha, COUNT(DISTINCT codigo_persona) as total_asistencias 
				FROM asistencia 
				WHERE tipo = 'Entrada' 
				GROUP BY DATE(fecha) 
				ORDER BY fecha DESC 
				LIMIT 5"; // Últimos 5 días
	
		return ejecutarConsulta($sql); // Retorna las asistencias diarias
	}
	

    //Función para verificar el acceso al sistema
    public function verificar($login, $clave) {
        $sql = "SELECT u.codigo_persona, u.idusuario, u.nombre, u.apellidos, u.login, u.idtipousuario, u.iddepartamento, u.email, u.imagen, u.login, tu.nombre as tipousuario 
                FROM usuarios u 
                INNER JOIN tipousuario tu ON u.idtipousuario = tu.idtipousuario 
                WHERE login='$login' AND password='$clave' AND estado='1'";
        return ejecutarConsulta($sql);  
    }
}
?>
