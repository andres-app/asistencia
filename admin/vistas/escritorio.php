<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
} else {
    require 'header.php';
    require_once('../modelos/Usuario.php');
    $usuario = new Usuario();

    // Obtenemos datos para estadísticas
    $total_usuarios = $usuario->cantidad_usuario()->fetch_object()->total_usuarios;
    
    // Obtenemos solo la primera entrada del día de cada usuario
    $total_asistencias_hoy_result = $usuario->total_asistencias_hoy();
    $total_asistencias_hoy = isset($total_asistencias_hoy_result['total_asistencias']) ? $total_asistencias_hoy_result['total_asistencias'] : 0;

    // Obtenemos el resultado de empleados sin asistencia
    $empleados_sin_asistencia_result = $usuario->empleados_sin_asistencia();
    $empleados_sin_asistencia = isset($empleados_sin_asistencia_result['empleados_sin_asistencia']) ? $empleados_sin_asistencia_result['empleados_sin_asistencia'] : 0;

    ?>
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="panel-body">
                            <!-- Condición para Administrador -->
                            <?php if ($_SESSION['tipousuario'] == 'Administrador') { ?>
                                
                                <!-- Asistencias Hoy -->
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h3><?php echo $total_asistencias_hoy; ?></h3>
                                            <p>Asistencias Hoy</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                        </div>
                                        <a href="asistencia.php" class="small-box-footer">Ver detalles <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <!-- Empleados sin asistencia hoy -->
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <h3><?php echo $empleados_sin_asistencia; ?></h3>
                                            <p>Empleados sin asistencia hoy</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                        </div>
                                        <a href="asistencia.php" class="small-box-footer">Ver detalles <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <!-- Total de Empleados -->
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="small-box bg-aqua">
                                        <div class="inner">
                                            <h3><?php echo $total_usuarios; ?></h3>
                                            <p>Total de Empleados</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-users" aria-hidden="true"></i>
                                        </div>
                                        <a href="usuario.php" class="small-box-footer">Ingresar <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <!-- Lista de Asistencias -->
                                <!-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <h4 style="font-size: 20px;">
                                                <strong>Lista asistencias</strong>
                                            </h4>
                                            <p>Módulo</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                        </div>
                                        <a href="asistencia.php" class="small-box-footer">Ingresar <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div> -->

                            <?php } else { ?>
                                <!-- Para los empleados no administradores -->
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <h5 style="font-size: 20px;">
                                                <strong>Mi lista de asistencias</strong>
                                            </h5>
                                            <p>Módulo</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                        </div>
                                        <a href="asistenciau.php" class="small-box-footer">Ingresar <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="small-box bg-purple">
                                        <div class="inner">
                                            <h5 style="font-size: 20px;">
                                                <strong>Mi reporte de asistencias</strong>
                                            </h5>
                                            <p>Módulo</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                        </div>
                                        <a href="rptasistenciau.php" class="small-box-footer">Ingresar <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

    <?php
    require 'footer.php';
}
ob_end_flush();
?>
