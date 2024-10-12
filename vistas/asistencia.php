<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Control | Asistencia</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <style>
        body {
            background: linear-gradient(to bottom, #1d2b64, #f8cdda);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .login-container {
            display: flex;
            width: 800px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .login-image {
            flex: 1;
            background: url('../admin/files/negocio/QT.png') no-repeat center center;
            background-size: cover;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            margin-left: 20px;
        }

        .login-form {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .login-form h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }

        .input-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            width: 100%;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 12px 40px;
            border: none;
            border-radius: 30px;
            background: #f2f2f2;
            font-size: 16px;
            color: #333;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            font-size: 16px;
            color: #aaa;
        }

        .login-button {
            width: 100%;
            padding: 12px;
            background: #333;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-button:hover {
            background: #555;
        }

        #current-time {
            margin-top: 20px;
            font-size: 24px;
            color: #333;
            font-weight: bold;
        }

        .lockscreen-footer {
            margin-top: 20px;
            text-align: center;
        }

        .lockscreen-footer a {
            color: #3498db;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Imagen de la izquierda -->
        <div class="login-image"></div>

        <!-- Formulario de la derecha -->
        <div class="login-form">
            <h2>Control de Asistencia</h2>
            <form action="" class="lockscreen-credentials" name="formulario" id="formulario" method="POST" onsubmit="return confirmarMarcado()">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" class="form-control" name="codigo_persona" id="codigo_persona" placeholder="ID de asistencia">
                </div>
                <button type="submit" class="login-button">Marcar</button>
            </form>

            <!-- Mostrar la hora actual -->
            <div id="current-time">00:00:00</div>

            <div class="lockscreen-footer">
                <a href="../admin/">Iniciar Sesión</a>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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

            document.getElementById("current-time").innerHTML = `<span>${hours}:${minutes}:${seconds}</span>`;
            setTimeout(updateTime, 1000);
        }

        updateTime();

        function confirmarMarcado() {
            var currentTime = new Date();
            var hours = currentTime.getHours();
            var minutes = currentTime.getMinutes();
            var seconds = currentTime.getSeconds();

            hours = (hours < 10 ? "0" : "") + hours;
            minutes = (minutes < 10 ? "0" : "") + minutes;
            seconds = (seconds < 10 ? "0" : "") + seconds;

            var horaMarcado = hours + ":" + minutes + ":" + seconds;

            // Mostrar el toaster
            toastr.success("Marcaste tu asistencia a las " + horaMarcado, "Confirmación");

            return false; // Previene el envío real del formulario
        }
    </script>

</body>

</html>
