<?php
include("template/cabecera_publica.php");
include("administrador/config/bd.php");

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mensaje = "";
$usuario = isset($_SESSION['usuarioPendiente']) ? $_SESSION['usuarioPendiente'] : '';

function generarCodigoActivacion($longitud = 6) {
    return strtoupper(substr(md5(time()), 0, $longitud));
}

function enviarCorreoActivacion($usuario, $nombre, $correo_institucional, $codigo_activacion) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'mail.smtp2go.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hector.alvarez2922@alumnos.udg.mx';
        $mail->Password = 'YZYfV2q9aqKI1Wtz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 2525;

        // Remitente y destinatarios
        $mail->setFrom('hector.alvarez2922@alumnos.udg.mx', 'UniBooks UDG');
        $mail->addAddress($correo_institucional);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Reenvio: Activacion de cuenta UniBooks UDG';
        $mail->Body    = "Hola $nombre,<br><br>Gracias por registrarte. Tu código de activación es: <b>$codigo_activacion</b><br><br>Por favor, ingresa este código en la página de activación para activar tu cuenta.<br><br>Saludos,<br>Equipo de UniBooks UDG";
        $mail->AltBody = "Hola $nombre,\n\nGracias por registrarte. Tu código de activación es: $codigo_activacion\n\nPor favor, ingresa este código en la página de activación para activar tu cuenta.\n\nSaludos,\nEquipo de UniBooks UDG";

        $mail->send();
        return "Correo de activación enviado con éxito. Por favor, revisa también la carpeta de spam o correos no deseados.";
    } catch (Exception $e) {
        return "No se pudo enviar el correo de activación. Error: {$mail->ErrorInfo}";
    }
}

if ($_POST) {
    if (isset($_POST['reenviar'])) {
        $usuario = strtoupper($_POST['usuario']);
        $verificarUsuario = $conexion->prepare("SELECT * FROM usuarios WHERE usuario=:usuario");
        $verificarUsuario->bindParam(':usuario', $usuario);
        $verificarUsuario->execute();
        $usuarioData = $verificarUsuario->fetch(PDO::FETCH_ASSOC);

        if ($usuarioData) {
            if ($usuarioData['activado'] == 1) {
                $mensaje = "El usuario ya está activado.";
            } else {
                $ultimoReenvio = $usuarioData['ultimo_reenvio'];
                $tiempoActual = time();
                $tiempoEspera = 180; // 3 minutos

                if ($ultimoReenvio && ($tiempoActual - $ultimoReenvio < $tiempoEspera)) {
                    $mensaje = "Debes esperar antes de solicitar otro reenvío.";
                } else {
                    $nombre = $usuarioData['nombre'];
                    $correo_institucional = $usuarioData['correo_institucional'];
                    $codigo_activacion = generarCodigoActivacion();

                    $actualizarCodigo = $conexion->prepare("UPDATE usuarios SET codigo_activacion=:codigo_activacion, ultimo_reenvio=:ultimo_reenvio WHERE usuario=:usuario");
                    $actualizarCodigo->bindParam(':codigo_activacion', $codigo_activacion);
                    $actualizarCodigo->bindParam(':ultimo_reenvio', $tiempoActual);
                    $actualizarCodigo->bindParam(':usuario', $usuario);
                    $actualizarCodigo->execute();

                    $mensaje = enviarCorreoActivacion($usuario, $nombre, $correo_institucional, $codigo_activacion);
                    $_SESSION['usuarioPendiente'] = $usuario;
                    $_SESSION['ultimoReenvio'] = $tiempoActual;
                }
            }
        } else {
            $mensaje = "Usuario no encontrado.";
        }
    } else {
        $usuario = strtoupper($_POST['usuario']);
        $codigo_activacion = $_POST['codigo_activacion'];

        $verificarCodigo = $conexion->prepare("SELECT * FROM usuarios WHERE usuario=:usuario AND codigo_activacion=:codigo_activacion");
        $verificarCodigo->bindParam(':usuario', $usuario);
        $verificarCodigo->bindParam(':codigo_activacion', $codigo_activacion);
        $verificarCodigo->execute();

        if ($verificarCodigo->fetch(PDO::FETCH_ASSOC)) {
            $activarUsuario = $conexion->prepare("UPDATE usuarios SET activado=1, codigo_activacion=NULL WHERE usuario=:usuario");
            $activarUsuario->bindParam(':usuario', $usuario);
            $activarUsuario->execute();
            $mensaje = "Activación exitosa. Ahora puedes iniciar sesión.";
            unset($_SESSION['usuarioPendiente']);
        } else {
            $mensaje = "Código de activación incorrecto.";
        }
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-body">
                    <h3 class="card-title text-center" style="color: #800000;">Activar Cuenta</h3>
                    <?php if ($mensaje) { ?>
                        <div class="alert alert-info" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php } ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="usuario" style="color: #333;">Usuario:</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese su usuario" value="<?php echo $usuario; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="codigo_activacion" style="color: #333;">Código de Activación:</label>
                            <input type="text" class="form-control" id="codigo_activacion" name="codigo_activacion" placeholder="Ingrese su código de activación" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" style="background-color: #800000; border-color: #800000;">Activar</button>
                    </form>
                </div>
            </div>
            <div class="card border-0 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-center" style="color: #800000;">Reenviar Código de Activación</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="usuarioReenvio" style="color: #333;">Usuario:</label>
                            <input type="text" class="form-control" id="usuarioReenvio" name="usuario" placeholder="Ingrese su usuario para reenvío" required>
                        </div>
                        <button type="submit" name="reenviar" class="btn btn-secondary btn-block" id="reenviarCorreo" style="background-color: #800000; border-color: #800000;">Reenviar Correo de Activación</button>
                        <small id="tiempoRestante" class="form-text text-muted text-center mt-2"></small>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("template/pie.php"); ?>

<!-- Optional JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var reenviarBtn = document.getElementById('reenviarCorreo');
        var tiempoRestante = document.getElementById('tiempoRestante');
        var tiempoEspera = 180; // 3 minutos en segundos
        var usuarioReenvio = document.getElementById('usuarioReenvio').value;
        
        var ultimoReenvio = <?php
        if (!empty($usuario)) {
            $verificarUsuario = $conexion->prepare("SELECT ultimo_reenvio FROM usuarios WHERE usuario=:usuario");
            $verificarUsuario->bindParam(':usuario', $usuario);
            $verificarUsuario->execute();
            $usuarioData = $verificarUsuario->fetch(PDO::FETCH_ASSOC);
            echo $usuarioData ? $usuarioData['ultimo_reenvio'] : 0;
        } else {
            echo 0;
        }
        ?>;
        var ahora = Math.floor(Date.now() / 1000);

        if (ultimoReenvio && (ahora - ultimoReenvio < tiempoEspera)) {
            var tiempoRestanteSegundos = tiempoEspera - (ahora - ultimoReenvio);
            reenviarBtn.disabled = true;

            var interval = setInterval(function() {
                tiempoRestanteSegundos--;
                if (tiempoRestanteSegundos <= 0) {
                    clearInterval(interval);
                    reenviarBtn.disabled = false;
                    tiempoRestante.textContent = "";
                } else {
                    var minutos = Math.floor(tiempoRestanteSegundos / 60);
                    var segundos = tiempoRestanteSegundos % 60;
                    tiempoRestante.textContent = "Puedes reenviar el correo en " + minutos + " minuto(s) y " + segundos + " segundo(s).";
                }
            }, 1000);
        } else {
            reenviarBtn.disabled = false;
        }
    });
</script>
