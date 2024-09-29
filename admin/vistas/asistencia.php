<?php
// Activamos almacenamiento en el buffer
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
                            <h1 class="box-title">Lista de Asistencia</h1>
                            <div class="box-tools pull-right"></div>
                        </div>

                        <!-- Panel de listado -->
                        <div class="panel-body table-responsive" id="listadoregistros">
                            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                    <th>Opciones</th>
                                    <th>Código</th>
                                    <th>Nombres</th>
                                    <th>Área</th>
                                    <th>Fecha Hora</th>
                                    <th>Asistencia</th>
                                    <th>Fecha</th>
                                </thead>
                            </table>
                        </div>

                        <!-- Formulario para editar registro de asistencia -->
                        <div class="panel-body" id="formularioregistros" style="display:none;">
                            <form action="" name="formulario" id="formulario" method="POST">
                                <!-- Campo oculto para el ID de asistencia -->
                                <input type="hidden" name="idasistencia" id="idasistencia">

                                <!-- Otros campos del formulario -->
                                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                    <label for="">Código Persona(*):</label>
                                    <input readonly class="form-control" type="text" name="codigo_persona"
                                        id="codigo_persona" maxlength="20" placeholder="Código Persona" required>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                    <label for="">Nombre</label>
                                    <input disabled class="form-control" type="text" name="nombre" id="nombre"
                                        maxlength="20" required>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                    <label for="">Tipo de Registro(*):</label>
                                    <input readonly class="form-control" type="text" name="tipo" id="tipo" maxlength="20"
                                        placeholder="Tipo de Registro" required>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                    <label for="">Fecha/Hora(*):</label>
                                    <input class="form-control" type="datetime-local" name="fecha_hora" id="fecha_hora"
                                        required>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>
                                        Guardar</button>
                                    <button class="btn btn-danger" onclick="cancelarform()" type="button"><i
                                            class="fa fa-arrow-circle-left"></i> Cancelar</button>
                                </div>
                            </form>
                        </div>



                    </div>
                </div>
            </div>

        </section>
        <!-- Modal de confirmación -->
        <div class="modal fade" id="modalConfirmar" tabindex="-1" role="dialog" aria-labelledby="modalConfirmarLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalConfirmarLabel">Confirmar modificación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas modificar la fecha/hora? Esto se registrará en la auditoría.</p>
                        <div class="form-group">
                            <label for="motivo">Motivo de la modificación:</label>
                            <textarea class="form-control" id="motivo" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="confirmarGuardar">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php
    require 'footer.php';
    ?>
    <script src="scripts/asistencia.js"></script>
    <?php
}
ob_end_flush();
?>