<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Control | Asistencia</title>
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../admin/public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../admin/public/css/font-awesome.css">
    <!-- Estilo del tema -->
    <link rel="stylesheet" href="../admin/public/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../admin/public/css/blue.css">
    <link rel="shortcut icon" href="../admin/public/img/favicon.ico">
    <style>
        body {
            background: linear-gradient(to bottom, #3498db, #2c3e50);
            color: #fff;
            margin: 0;
        }

        .lockscreen-wrapper {
            max-width: 400px;
            margin: 0 auto;
            text-align: center;
            padding: 60px;
            box-sizing: border-box;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            border-radius: 20px;
        }

        .lockscreen-logo,
        .lockscreen-name,
        .lockscreen-item,
        .help-block,
        .lockscreen-footer {
            text-align: center;
        }

        .lockscreen-item {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(160, 32, 240);
            margin-top: 20px;
        }

        #current-time {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            color: #000000;
        }
    </style>
</head>

<body class="hold-transition lockscreen">

    <div class="lockscreen-wrapper">
        <?php //include '../ajax/asistencia.php' 
        ?>
        <div name="movimientos" id="movimientos"></div>

        <div class="lockscreen-logo">
            <a href="#"><b>Control</b> ASISTENCIA</a>
        </div>

        <div class="lockscreen-item">
            <div class="lockscreen-image">
                <img src="../admin/files/negocio/default.jpg" alt="User Image">
            </div>

            <form action="" class="lockscreen-credentials" name="formulario" id="formulario" method="POST">
                <div class="input-group">
                    <input type="password" class="form-control" name="codigo_persona" id="codigo_persona" placeholder="ID de asistencia">
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-arrow-right text-muted"></i></button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Mostrar la hora actual -->
        <div id="current-time">00:00:00</div>

        <div class="lockscreen-footer">
            <a href="../admin/">Iniciar Sesión</a>
        </div>
    </div>

    <!-- jQuery -->
    <script src="../admin/public/js/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../admin/public/js/bootstrap.min.js"></script>
    <!-- Bootbox -->
    <script src="../admin/public/js/bootbox.min.js"></script>

    <!-- JavaScript para actualizar la hora -->
    <script>
        function updateTime() {
            var currentTime = new Date();
            var hours = currentTime.getHours();
            var minutes = currentTime.getMinutes();
            var seconds = currentTime.getSeconds();

            hours = (hours < 10 ? "0" : "") + hours;
            minutes = (minutes < 10 ? "0" : "") + minutes;
            seconds = (seconds < 10 ? "0" : "") + seconds;

            document.getElementById("current-time").innerHTML = `<span style="font-size: 24px; font-weight: bold;">${hours}:${minutes}:${seconds}</span>`;
            setTimeout(updateTime, 1000);
        }

        updateTime();
    </script>

    <!-- Tus otros scripts -->
    <script type="text/javascript" src="scripts/asistencia.js"></script>

</body>

</html>