<?php
// Incluir la conexión a la base de datos
require "../config/Conexion.php";

class Asistencia
{

    // Implementamos nuestro constructor
    public function __construct()
    {
    }

    // Método para listar todos los registros
    public function listar()
    {
        $sql = "SELECT a.idasistencia,a.codigo_persona,a.fecha_hora,a.tipo,a.fecha,u.nombre,u.apellidos,d.nombre as departamento 
                FROM asistencia a 
                INNER JOIN usuarios u INNER JOIN departamento d 
                ON u.iddepartamento=d.iddepartamento 
                WHERE a.codigo_persona=u.codigo_persona";
        return ejecutarConsulta($sql);
    }

    // Método para listar registros de un usuario específico
    public function listaru($idusuario)
    {
        $sql = "SELECT a.idasistencia,a.codigo_persona,a.fecha_hora,a.tipo,a.fecha,u.nombre,u.apellidos,d.nombre as departamento 
                FROM asistencia a 
                INNER JOIN usuarios u INNER JOIN departamento d 
                ON u.iddepartamento=d.iddepartamento 
                WHERE a.codigo_persona=u.codigo_persona AND u.idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    // Método para listar asistencias por fechas y, opcionalmente, por un código de persona específico
    public function listar_asistencia($fecha_inicio, $fecha_fin, $codigo_persona)
    {
        if ($codigo_persona == '') {
            $sql = "SELECT a.idasistencia, a.codigo_persona, a.fecha_hora, a.tipo, a.fecha, u.nombre, u.apellidos 
                    FROM asistencia a 
                    INNER JOIN usuarios u ON a.codigo_persona = u.codigo_persona 
                    WHERE DATE(a.fecha) >= '$fecha_inicio' AND DATE(a.fecha) <= '$fecha_fin'";
        } else {
            $sql = "SELECT a.idasistencia, a.codigo_persona, a.fecha_hora, a.tipo, a.fecha, u.nombre, u.apellidos 
                    FROM asistencia a 
                    INNER JOIN usuarios u ON a.codigo_persona = u.codigo_persona 
                    WHERE DATE(a.fecha) >= '$fecha_inicio' AND DATE(a.fecha) <= '$fecha_fin' 
                    AND a.codigo_persona = '$codigo_persona'";
        }

        return ejecutarConsulta($sql);
    }

    // Método para insertar un nuevo registro
    public function insertar($codigo_persona, $fecha_hora, $tipo)
    {
        $sql = "INSERT INTO asistencia (codigo_persona, fecha_hora, tipo) 
                VALUES ('$codigo_persona', '$fecha_hora', '$tipo')";
        return ejecutarConsulta($sql);
    }

    // Método para editar un registro existente
    public function editar($idasistencia, $codigo_persona, $fecha_hora, $tipo)
    {
        $sql = "UPDATE asistencia 
                SET codigo_persona = '$codigo_persona', fecha_hora = '$fecha_hora', tipo = '$tipo' 
                WHERE idasistencia = '$idasistencia'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar un registro específico
    public function mostrar($idasistencia)
    {
        // Hacemos un JOIN para obtener el nombre desde la tabla usuarios
        $sql = "SELECT a.idasistencia, a.codigo_persona, u.nombre, a.fecha_hora, a.tipo 
                FROM asistencia a 
                INNER JOIN usuarios u ON a.codigo_persona = u.codigo_persona 
                WHERE a.idasistencia = '$idasistencia'";
        return ejecutarConsultaSimpleFila($sql);
    }

}
?>