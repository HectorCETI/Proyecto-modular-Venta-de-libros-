<?php 
session_start();
include("config/bd.php");

if ($_POST) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Verificar si el usuario está bloqueado antes de procesar el intento de inicio de sesión
    $sentenciaSQL = $conexion->prepare("SELECT * FROM login_attempts WHERE usuario=:usuario ORDER BY attempt_time DESC LIMIT 1");
    $sentenciaSQL->bindParam(':usuario', $usuario);
    $sentenciaSQL->execute();
    $ultimoIntento = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

    $bloqueoTiempo = 0; // En segundos
    if ($ultimoIntento && $ultimoIntento['bloqueo_until'] && strtotime($ultimoIntento['bloqueo_until']) > time()) {
        $bloqueoTiempo = strtotime($ultimoIntento['bloqueo_until']) - time();
    }

    if ($bloqueoTiempo > 0) {
        $mensaje = "Acceso bloqueado. Inténtalo de nuevo en <span id='contador-bloqueo'>" . gmdate("i:s", $bloqueoTiempo) . "</span>.";
    } else {
        // Obtener los intentos de inicio de sesión fallidos recientes
        $intentosSQL = $conexion->prepare("SELECT COUNT(*) as intentos FROM login_attempts WHERE usuario=:usuario AND attempt_time > (NOW() - INTERVAL 5 MINUTE)");
        $intentosSQL->bindParam(':usuario', $usuario);
        $intentosSQL->execute();
        $intentos = $intentosSQL->fetch(PDO::FETCH_ASSOC)['intentos'];

        $sentenciaSQL = $conexion->prepare("SELECT * FROM admin WHERE usuario=:usuario");
        $sentenciaSQL->bindParam(':usuario', $usuario);
        $sentenciaSQL->execute();
        $admin = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($contrasena, $admin['contrasena'])) {
            $_SESSION['usuario'] = "OK";
            $_SESSION['nombreUsuario'] = "Administrador";
            header('Location:inicio.php');
            exit;
        } else {
            // Registrar el intento fallido
            $sentenciaSQL = $conexion->prepare("INSERT INTO login_attempts (usuario, bloqueo_until) VALUES (:usuario, NULL)");
            $sentenciaSQL->bindParam(':usuario', $usuario);
            $sentenciaSQL->execute();

            $mensaje = "Usuario o contraseña incorrectos";
            $intentos++;
            if ($intentos >= 3) {
                $bloqueoTiempo = 60; // 1 minuto de bloqueo
                $bloqueo_until = date("Y-m-d H:i:s", time() + $bloqueoTiempo);
                $updateSQL = $conexion->prepare("UPDATE login_attempts SET bloqueo_until=:bloqueo_until WHERE usuario=:usuario ORDER BY attempt_time DESC LIMIT 1");
                $updateSQL->bindParam(':bloqueo_until', $bloqueo_until);
                $updateSQL->bindParam(':usuario', $usuario);
                $updateSQL->execute();

                $mensaje = "Acceso bloqueado por <span id='contador-bloqueo'>" . gmdate("i:s", $bloqueoTiempo) . "</span>.";
            }
        }
    }
}
?>

<?php include("../template/cabecera_publica.php"); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <h3 class="card-title text-center" style="color: #800000;">Iniciar Sesión</h3>
                    <?php if (isset($mensaje)) { ?>
                        <div class="alert alert-danger" role="alert" id="mensaje-alerta">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php } ?>
                    <form method="POST" id="login-form">
                        <div class="form-group">
                            <label for="usuario" style="color: #333;">Usuario:</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese su usuario">
                        </div>
                        <div class="form-group">
                            <label for="contrasena" style="color: #333;">Contraseña:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="toggle-password" aria-label="Mostrar u ocultar contraseña">
                                        <i class="bi bi-eye" id="toggle-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" style="background-color: #800000; border-color: #800000;">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("../template/pie.php"); ?>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGaS3ukQmTktG8f5DpiUibVx3" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIyFEYeDjAxZw8++PpRtW0uChFfYCAaMSFZcUOLO" crossorigin="anonymous"></script>
<script>
    document.getElementById('toggle-password').addEventListener('click', function() {
        var passwordInput = document.getElementById('contrasena');
        var icon = document.getElementById('toggle-icon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });

    // Mostrar el tiempo restante de bloqueo
    <?php if (isset($bloqueoTiempo) && $bloqueoTiempo > 0) { ?>
        var bloqueoTiempo = <?php echo $bloqueoTiempo; ?>;
        var contadorBloqueo = document.getElementById('contador-bloqueo');
        var bloqueoInterval = setInterval(function() {
            bloqueoTiempo--;
            if (bloqueoTiempo <= 0) {
                clearInterval(bloqueoInterval);
                document.getElementById('login-form').querySelectorAll('input, button').forEach(function(el) {
                    el.disabled = false;
                });
                document.getElementById('mensaje-alerta').style.display = 'none';
                alert('El bloqueo ha terminado. Si te vuelves a equivocar, se bloqueará por 1m.');
            } else {
                var minutes = Math.floor(bloqueoTiempo / 60);
                var seconds = bloqueoTiempo % 60;
                contadorBloqueo.textContent = minutes + "m " + (seconds < 10 ? "0" : "") + seconds + "s";
            }
        }, 1000);

        // Deshabilitar el formulario si el usuario está bloqueado
        document.getElementById('login-form').querySelectorAll('input, button').forEach(function(el) {
            el.disabled = true;
        });
    <?php } else { ?>
        document.getElementById('login-form').onsubmit = function() {
            var usuario = document.getElementById('usuario').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'check_block.php', false); // Solicitud síncrona
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('usuario=' + usuario);

            if (xhr.responseText === 'blocked') {
                alert('Acceso bloqueado. Por favor, inténtalo más tarde.');
                return false; // Prevenir el envío del formulario
            }
        };
    <?php } ?>
</script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    .card {
        border-radius: 8px;
        transition: none; /* Eliminar cualquier animación */
        background-color: #f8f9fa;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .card-body {
        padding: 30px;
    }
    h3 {
        font-family: 'Arial', sans-serif;
        font-size: 1.75rem;
        color: #800000;
    }
    label {
        font-family: 'Arial', sans-serif;
        color: #333;
    }
    .form-control {
        border-radius: 8px;
    }
    .btn {
        font-family: 'Arial', sans-serif';
        font-size: 1rem;
        font-weight: bold;
    }
    .input-group {
        display: flex;
        align-items: center;
    }
    .input-group-append {
        cursor: pointer;
        margin-left: 10px;
    }
    .bi {
        font-size: 1.2rem;
        color: #333;
    }
</style>
</body>
</html>
