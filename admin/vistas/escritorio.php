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
  $rsptan = $usuario->cantidad_usuario();
  $reg = $rsptan->fetch_object();
  $reg->nombre;
?>
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="panel-body">

              <?php if ($_SESSION['tipousuario'] == 'Administrador') {
              ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-yellow">
                    <div class="inner">
                      <h4 style="font-size: 20px;">
                        <strong>Lista asistencias </strong>
                      </h4>
                      <p>Módulo</p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-list" aria-hidden="true"></i>
                    </div>
                    <a href="asistencia.php" class="small-box-footer">Ingresar <i class="fa fa-arrow-circle-right"></i></a>
                    </a>
                  </div>
                </div>
              <?php } ?>
              <?php if ($_SESSION['tipousuario'] != 'Administrador') {
              ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-yellow">

                    <a href="asistenciau.php" class="small-box-footer">
                      <div class="inner">
                        <h5 style="font-size: 20px;">
                          <strong>Mi lista asistencias </strong>
                        </h5>
                        <p>Módulo</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-list" aria-hidden="true"></i>
                      </div>&nbsp;
                      <div class="small-box-footer">
                        <i class="fa"></i>
                      </div>

                    </a>
                  </div>
                </div>
              <?php } ?>



              <?php if ($_SESSION['tipousuario'] == 'Administrador') {
              ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-yellow">
                    <div class="inner">
                      <h4 style="font-size: 20px;">
                        <strong>Empleados: </strong>
                      </h4>
                      <p>Total <?php echo $reg->nombre; ?></p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-users" aria-hidden="true"></i>
                    </div>
                    <a href="usuario.php" class="small-box-footer">Ingresar <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
              <?php } ?>


              <?php if ($_SESSION['tipousuario'] == 'Administrador') {
              ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-yellow">

                    <a href="rptasistencia.php" class="small-box-footer">
                      <div class="inner">
                        <h5 style="font-size: 20px;">
                          <strong>Reporte de asistencias </strong>
                        </h5>
                        <p>Módulo</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-list" aria-hidden="true"></i>
                      </div>&nbsp;
                      <div class="small-box-footer">
                        <i class="fa"></i>
                      </div>

                    </a>
                  </div>
                </div>
              <?php } ?>
              <?php if ($_SESSION['tipousuario'] != 'Administrador') {
              ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-purple">

                    <a href="rptasistenciau.php" class="small-box-footer">
                      <div class="inner">
                        <h5 style="font-size: 20px;">
                          <strong>Mi reporte de asistencias </strong>
                        </h5>
                        <p>Módulo</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-list" aria-hidden="true"></i>
                      </div>&nbsp;
                      <div class="small-box-footer">
                        <i class="fa"></i>
                      </div>

                    </a>
                  </div>
                </div>
              <?php } ?>
              <!--fin centro-->
            </div>
            <div class="box box-danger">
              <div class="box-header with-border">
                <h3 class="box-title">Donut Chart</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <div class="box-body">
                <canvas id="pieChart" style="height: 335px; width: 670px;" height="670" width="1340"></canvas>
              </div>

            </div>
          </div>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->

  </div>



<?php
  require 'footer.php';
}
ob_end_flush();
?>