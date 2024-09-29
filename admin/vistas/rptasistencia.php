<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
} else {

  require 'header.php';

  ?>
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h1 class="box-title">Consulta de Asistencias por Fecha</h1>
              <div class="box-tools pull-right"></div>
            </div>
            <!--box-header-->
            <!--centro-->
            <div class="panel-body table-responsive" id="listadoregistros">
              <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <label>Fecha Inicio</label>
                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio"
                  value="<?php echo date("Y-m-01"); ?>">
              </div>
              <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <label>Fecha Fin</label>
                <input type="date" class="form-control" name="fecha_fin" id="fecha_fin"
                  value="<?php echo date("Y-m-d"); ?>">
              </div>
              <div class="form-inline col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label>Empleado</label>
                <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true"
                  required></select>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 pt-3" style="padding-top: 24px">
                <button class="btn btn-success" onclick="listar_asistencia();">Mostrar</button>
                <a href="javascript:void(0);" onclick="generarPDF();" class="btn btn-danger">
                  <i class="fa fa-file-pdf-o" style="font-size: 18px;"></i>
                </a>
              </div>


              <!-- Agregamos la tabla dentro del panel-body -->
              <table id="tbllistado_asistencia" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th>Fecha</th>
                  <th>Nombres</th>
                  <th>Asistencia</th>
                  <th>Fecha/Hora</th>
                  <th>Código</th>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!--fin centro-->
    </section>
    <!-- /.content -->
  </div>

  <?php
  require 'footer.php';
  ?>
  <script src="scripts/asistencia.js"></script>
  <?php
}

ob_end_flush();
?>