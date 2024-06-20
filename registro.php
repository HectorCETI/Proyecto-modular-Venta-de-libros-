<?php
include("template/cabecera_publica.php");
include("administrador/config/bd.php");

require 'vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mensaje = "";
$usuarioExistente = false;
$telefonoExistente = false;
$correoExistente = false;

function generarCodigoActivacion($longitud = 6) {
    return strtoupper(substr(md5(time()), 0, $longitud));
}

if ($_POST) {
    $usuario = strtoupper($_POST['usuario']);
    $contrasena = $_POST['contrasena'];
    $nombre = strtoupper($_POST['nombre']);
    $apellido = strtoupper($_POST['apellido']);
    $correo_institucional = strtolower($_POST['correo_institucional']) . strtolower($_POST['correo_dominio']);
    $telefono = $_POST['telefono'];
    $centro_universitario = $_POST['centro_universitario'];
    $codigo_activacion = generarCodigoActivacion();

    // Verificar si el nombre de usuario ya existe
    $verificarUsuario = $conexion->prepare("SELECT * FROM usuarios WHERE usuario=:usuario");
    $verificarUsuario->bindParam(':usuario', $usuario);
    $verificarUsuario->execute();
    if ($verificarUsuario->fetch(PDO::FETCH_ASSOC)) {
        $usuarioExistente = true;
        $mensaje .= "El nombre de usuario ya está en uso. ";
    }

    // Verificar si el teléfono ya existe
    $verificarTelefono = $conexion->prepare("SELECT * FROM usuarios WHERE telefono=:telefono");
    $verificarTelefono->bindParam(':telefono', $telefono);
    $verificarTelefono->execute();
    if ($verificarTelefono->fetch(PDO::FETCH_ASSOC)) {
        $telefonoExistente = true;
        $mensaje .= "El número de teléfono ya está en uso. ";
    }

    // Verificar si el correo institucional ya existe
    $verificarCorreo = $conexion->prepare("SELECT * FROM usuarios WHERE correo_institucional=:correo_institucional");
    $verificarCorreo->bindParam(':correo_institucional', $correo_institucional);
    $verificarCorreo->execute();
    if ($verificarCorreo->fetch(PDO::FETCH_ASSOC)) {
        $correoExistente = true;
        $mensaje .= "El correo institucional ya está en uso. ";
    }

    if (!preg_match('/^[A-Z0-9_]{6,12}$/', $usuario)) {
        $mensaje = "El nombre de usuario debe tener entre 6 y 12 caracteres, y solo puede contener letras, números y guiones bajos.";
    } elseif ($usuarioExistente) {
        // No hacer nada, el mensaje ya está agregado
    } elseif (!preg_match('/^\d{10}$/', $telefono)) {
        $mensaje = "El número de teléfono debe tener 10 dígitos.";
    } elseif ($telefonoExistente) {
        // No hacer nada, el mensaje ya está agregado
    } elseif (!preg_match('/@alumnos.udg.mx$|@academicos.udg.mx$/', $correo_institucional)) {
        $mensaje = "El correo institucional debe terminar con '@alumnos.udg.mx' o '@academicos.udg.mx'.";
    } elseif ($correoExistente) {
        // No hacer nada, el mensaje ya está agregado
    } else {
        // Cifrar la contraseña
        $contrasenaCifrada = password_hash($contrasena, PASSWORD_BCRYPT);

        // Insertar el nuevo usuario en la base de datos
        $sentenciaSQL = $conexion->prepare("INSERT INTO usuarios (usuario, contrasena, nombre, apellido, correo_institucional, telefono, centro_universitario, codigo_activacion, activado) VALUES (:usuario, :contrasena, :nombre, :apellido, :correo_institucional, :telefono, :centro_universitario, :codigo_activacion, 0)");
        $sentenciaSQL->bindParam(':usuario', $usuario);
        $sentenciaSQL->bindParam(':contrasena', $contrasenaCifrada);
        $sentenciaSQL->bindParam(':nombre', $nombre);
        $sentenciaSQL->bindParam(':apellido', $apellido);
        $sentenciaSQL->bindParam(':correo_institucional', $correo_institucional);
        $sentenciaSQL->bindParam(':telefono', $telefono);
        $sentenciaSQL->bindParam(':centro_universitario', $centro_universitario);
        $sentenciaSQL->bindParam(':codigo_activacion', $codigo_activacion);
        $sentenciaSQL->execute();

        // Enviar correo de activación
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
            $mail->Subject = 'Activacion de cuenta UniBooks UDG';
            $mail->Body    = "Hola $nombre,<br><br>Gracias por registrarte. Tu código de activación es: <b>$codigo_activacion</b><br><br>Por favor, ingresa este código en la página de activación para activar tu cuenta.<br><br>Saludos,<br>Equipo de UniBooks UDG";
            $mail->AltBody = "Hola $nombre,\n\nGracias por registrarte. Tu código de activación es: $codigo_activacion\n\nPor favor, ingresa este código en la página de activación para activar tu cuenta.\n\nSaludos,\nEquipo de UniBooks UDG";

            $mail->send();
            $mensaje = "Registro exitoso. Se ha enviado un código de activación a tu correo institucional. Por favor, revisa también la carpeta de spam o correos no deseados.";
        } catch (Exception $e) {
            $mensaje = "Registro exitoso. No se pudo enviar el correo de activación. Por favor, intenta de nuevo más tarde. Error: {$mail->ErrorInfo}";
        }

        // Limpiar formulario
        $_POST = array();
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-center" style="color: #800000;">Registro de Usuario</h3>
                    <?php if ($mensaje) { ?>
                        <div class="alert alert-info" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php } ?>
                    <form method="POST" onsubmit="return validarFormulario()">
                        <div class="form-group">
                            <label for="usuario" style="color: #333;">Usuario:</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese su usuario" value="<?php echo isset($_POST['usuario']) ? $_POST['usuario'] : ''; ?>" required>
                            <small id="usuarioAlert" class="form-text text-danger" style="display: none;">El nombre de usuario debe tener entre 5 y 12 caracteres, y solo puede contener letras, números y guiones bajos.</small>
                            <small id="usuarioExistenteAlert" class="form-text text-danger" style="display: <?php echo $usuarioExistente ? 'block' : 'none'; ?>;">El nombre de usuario ya está en uso. Por favor, elige otro.</small>
                        </div>
                        <div class="form-group">
                            <label for="contrasena" style="color: #333;">Contraseña:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="toggle-password" aria-label="Mostrar u ocultar contraseña">
                                        <i class="bi bi-eye" id="toggle-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="progress mt-2" id="passwordStrengthBar">
                                <div class="progress-bar" role="progressbar" style="width: 0%;"></div>
                            </div>
                            <small id="passwordStrengthText" class="form-text text-muted"></small>
                            <small id="contrasenaAlert" class="form-text text-danger" style="display: none;">La contraseña debe tener al menos 8 caracteres y cumplir con los requisitos de seguridad.</small>
                        </div>
                        <div class="form-group">
                            <label for="nombre" style="color: #333;">Primer Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre" value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : ''; ?>" required maxlength="20">
                            <small id="nombreAlert" class="form-text text-danger" style="display: none;">El nombre solo puede contener letras.</small>
                        </div>
                        <div class="form-group">
                            <label for="apellido" style="color: #333;">Primer Apellido:</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese su apellido" value="<?php echo isset($_POST['apellido']) ? $_POST['apellido'] : ''; ?>" required maxlength="20">
                            <small id="apellidoAlert" class="form-text text-danger" style="display: none;">El apellido solo puede contener letras.</small>
                        </div>
                        <div class="form-group">
                            <label for="correo_institucional" style="color: #333;">Correo Institucional:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="correo_institucional" name="correo_institucional" placeholder="Ingrese su correo institucional" value="<?php echo isset($_POST['correo_institucional']) ? $_POST['correo_institucional'] : ''; ?>" required maxlength="30">
                                <select class="form-control" id="correo_dominio" name="correo_dominio" required>
                                    <option value="@alumnos.udg.mx" <?php echo (isset($_POST['correo_dominio']) && $_POST['correo_dominio'] == '@alumnos.udg.mx') ? 'selected' : ''; ?>>@alumnos.udg.mx</option>
                                    <option value="@academicos.udg.mx" <?php echo (isset($_POST['correo_dominio']) && $_POST['correo_dominio'] == '@academicos.udg.mx') ? 'selected' : ''; ?>>@academicos.udg.mx</option>
                                </select>
                            </div>
                            <small id="correoAlert" class="form-text text-danger" style="display: none;">Por favor, ingresa solo la parte anterior al '@' de tu correo institucional válido de la Universidad de Guadalajara. Esta plataforma está destinada únicamente para estudiantes de la UDG.</small>
                            <small id="correoExistenteAlert" class="form-text text-danger" style="display: <?php echo $correoExistente ? 'block' : 'none'; ?>;">El correo institucional ya está en uso. Por favor, elige otro.</small>
                        </div>
                        <div class="form-group">
                            <label for="telefono" style="color: #333;">Teléfono:</label>
                            <div class="input-group" style="max-width: 250px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                </div>
                                <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Ingrese su teléfono" value="<?php echo isset($_POST['telefono']) ? $_POST['telefono'] : ''; ?>" required maxlength="10">
                            </div>
                            <small id="telefonoAlert" class="form-text text-danger" style="display: none;">El número de teléfono debe tener 10 dígitos.</small>
                            <small id="telefonoExistenteAlert" class="form-text text-danger" style="display: <?php echo $telefonoExistente ? 'block' : 'none'; ?>;">El número de teléfono ya está en uso. Por favor, elige otro.</small>
                        </div>
                        <div class="form-group">
                            <label for="centro_universitario" style="color: #333;">Centro Universitario:</label>
                            <select class="form-control" id="centro_universitario" name="centro_universitario" required>
                                <option value="">Seleccione su centro universitario</option>
                                <optgroup label="Centros Universitarios">
                                    <option value="CUCS" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUCS') ? 'selected' : ''; ?>>CUCS - Centro Universitario de Ciencias de la Salud</option>
                                    <option value="CUCEI" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUCEI') ? 'selected' : ''; ?>>CUCEI - Centro Universitario de Ciencias Exactas e Ingenierías</option>
                                    <option value="CUCEA" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUCEA') ? 'selected' : ''; ?>>CUCEA - Centro Universitario de Ciencias Económico Administrativas</option>
                                    <option value="CUCSH" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUCSH') ? 'selected' : ''; ?>>CUCSH - Centro Universitario de Ciencias Sociales y Humanidades</option>
                                    <option value="CUAAD" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUAAD') ? 'selected' : ''; ?>>CUAAD - Centro Universitario de Arte, Arquitectura y Diseño</option>
                                    <option value="CUALTOS" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUALTOS') ? 'selected' : ''; ?>>CUALTOS - Centro Universitario de los Altos</option>
                                    <option value="CUCIÉNEGA" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUCIÉNEGA') ? 'selected' : ''; ?>>CUCIÉNEGA - Centro Universitario de la Ciénega</option>
                                    <option value="CUNORTE" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUNORTE') ? 'selected' : ''; ?>>CUNORTE - Centro Universitario del Norte</option>
                                    <option value="CUSUR" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUSUR') ? 'selected' : ''; ?>>CUSUR - Centro Universitario del Sur</option>
                                    <option value="CUCOSTA" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUCOSTA') ? 'selected' : ''; ?>>CUCOSTA - Centro Universitario de la Costa</option>
                                    <option value="CUValles" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUValles') ? 'selected' : ''; ?>>CUValles - Centro Universitario de los Valles</option>
                                    <option value="CUTonalá" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUTonalá') ? 'selected' : ''; ?>>CUTonalá - Centro Universitario de Tonalá</option>
                                    <option value="CUTlajomulco" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUTlajomulco') ? 'selected' : ''; ?>>CUTlajomulco - Centro Universitario de Tlajomulco</option>
                                    <option value="CUChapala" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUChapala') ? 'selected' : ''; ?>>CUChapala - Centro Universitario de Chapala</option>
                                    <option value="CUAmeca" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CUAmeca') ? 'selected' : ''; ?>>CUAmeca - Centro Universitario de Ameca</option>
                                    <option value="CULaBarca" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CULaBarca') ? 'selected' : ''; ?>>CULaBarca - Centro Universitario de La Barca</option>
                                    <option value="CULagos" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'CULagos') ? 'selected' : ''; ?>>CULagos - Centro Universitario de Lagos de Moreno</option>
                                </optgroup>
                                <optgroup label="Preparatorias">
                                    <option value="Prepa 1" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 1') ? 'selected' : ''; ?>>Prepa 1 - Escuela Preparatoria de Jalisco</option>
                                    <option value="Prepa 2" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 2') ? 'selected' : ''; ?>>Prepa 2 - Escuela Preparatoria No. 2</option>
                                    <option value="Prepa 3" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 3') ? 'selected' : ''; ?>>Prepa 3 - Escuela Preparatoria No. 3</option>
                                    <option value="Prepa 4" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 4') ? 'selected' : ''; ?>>Prepa 4 - Escuela Preparatoria No. 4</option>
                                    <option value="Prepa 5" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 5') ? 'selected' : ''; ?>>Prepa 5 - Escuela Preparatoria No. 5</option>
                                    <option value="Prepa 6" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 6') ? 'selected' : ''; ?>>Prepa 6 - Escuela Preparatoria No. 6</option>
                                    <option value="Prepa 7" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 7') ? 'selected' : ''; ?>>Prepa 7 - Escuela Preparatoria No. 7</option>
                                    <option value="Prepa 8" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 8') ? 'selected' : ''; ?>>Prepa 8 - Escuela Preparatoria No. 8</option>
                                    <option value="Prepa 9" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 9') ? 'selected' : ''; ?>>Prepa 9 - Escuela Preparatoria No. 9</option>
                                    <option value="Prepa 10" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 10') ? 'selected' : ''; ?>>Prepa 10 - Escuela Preparatoria No. 10</option>
                                    <option value="Prepa 11" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 11') ? 'selected' : ''; ?>>Prepa 11 - Escuela Preparatoria No. 11</option>
                                    <option value="Prepa 12" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 12') ? 'selected' : ''; ?>>Prepa 12 - Escuela Preparatoria No. 12</option>
                                    <option value="Prepa 13" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 13') ? 'selected' : ''; ?>>Prepa 13 - Escuela Preparatoria No. 13</option>
                                    <option value="Prepa 14" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa 14') ? 'selected' : ''; ?>>Prepa 14 - Escuela Preparatoria No. 14</option>
                                    <option value="Prepa Politécnica" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa Politécnica') ? 'selected' : ''; ?>>Prepa Politécnica - Escuela Politécnica</option>
                                    <option value="Prepa Vocacional" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa Vocacional') ? 'selected' : ''; ?>>Prepa Vocacional - Escuela Vocacional</option>
                                </optgroup>
                                <optgroup label="Preparatorias Regionales">
                                    <option value="Prepa Regional de Ameca" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa Regional de Ameca') ? 'selected' : ''; ?>>Prepa Regional de Ameca - Escuela Preparatoria Regional de Ameca</option>
                                    <option value="Prepa Regional de Chapala" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa Regional de Chapala') ? 'selected' : ''; ?>>Prepa Regional de Chapala - Escuela Preparatoria Regional de Chapala</option>
                                    <option value="Prepa Regional de Ciudad Guzmán" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa Regional de Ciudad Guzmán') ? 'selected' : ''; ?>>Prepa Regional de Ciudad Guzmán - Escuela Preparatoria Regional de Ciudad Guzmán</option>
                                    <option value="Prepa Regional de El Salto" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa Regional de El Salto') ? 'selected' : ''; ?>>Prepa Regional de El Salto - Escuela Preparatoria Regional de El Salto</option>
                                    <option value="Prepa Regional de Jocotepec" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa Regional de Jocotepec') ? 'selected' : ''; ?>>Prepa Regional de Jocotepec - Escuela Preparatoria Regional de Jocotepec</option>
                                    <option value="Prepa Regional de Lagos de Moreno" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa Regional de Lagos de Moreno') ? 'selected' : ''; ?>>Prepa Regional de Lagos de Moreno - Escuela Preparatoria Regional de Lagos de Moreno</option>
                                    <option value="Prepa Regional de Puerto Vallarta" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa Regional de Puerto Vallarta') ? 'selected' : ''; ?>>Prepa Regional de Puerto Vallarta - Escuela Preparatoria Regional de Puerto Vallarta</option>
                                    <option value="Prepa Regional de Tala" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa Regional de Tala') ? 'selected' : ''; ?>>Prepa Regional de Tala - Escuela Preparatoria Regional de Tala</option>
                                    <option value="Prepa Regional de Tepatitlán" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa Regional de Tepatitlán') ? 'selected' : ''; ?>>Prepa Regional de Tepatitlán - Escuela Preparatoria Regional de Tepatitlán</option>
                                    <option value="Prepa Regional de Tlajomulco" <?php echo (isset($_POST['centro_universitario']) && $_POST['centro_universitario'] == 'Prepa Regional de Tlajomulco') ? 'selected' : ''; ?>>Prepa Regional de Tlajomulco - Escuela Preparatoria Regional de Tlajomulco</option>
                                </optgroup>
                            </select>
                            <small id="centroUniversitarioAlert" class="form-text text-danger" style="display: none;">Por favor, seleccione su centro universitario.</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" style="background-color: #800000; border-color: #800000;">Registrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("template/pie.php"); ?>

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

    function validarFormulario() {
        var usuario = document.getElementById('usuario').value;
        var contrasena = document.getElementById('contrasena').value;
        var nombre = document.getElementById('nombre').value;
        var apellido = document.getElementById('apellido').value;
        var correo_institucional = document.getElementById('correo_institucional').value;
        var telefono = document.getElementById('telefono').value;
        var centro_universitario = document.getElementById('centro_universitario').value;
        var valido = true;

        // Validar usuario
        if (!/^[A-Z0-9_]{5,12}$/.test(usuario)) {
            document.getElementById('usuarioAlert').style.display = 'block';
            valido = false;
        } else {
            document.getElementById('usuarioAlert').style.display = 'none';
            validarUsuario(usuario);
        }

        // Validar contraseña
        var nivelSeguridad = evaluarContrasena(contrasena);
        if (nivelSeguridad < 2) {
            document.getElementById('contrasenaAlert').style.display = 'block';
            valido = false;
        } else {
            document.getElementById('contrasenaAlert').style.display = 'none';
        }

        // Validar nombre y apellido
        if (!/^[A-Z]+$/.test(nombre)) {
            document.getElementById('nombreAlert').style.display = 'block';
            valido = false;
        } else {
            document.getElementById('nombreAlert').style.display = 'none';
        }

        if (!/^[A-Z]+$/.test(apellido)) {
            document.getElementById('apellidoAlert').style.display = 'block';
            valido = false;
        } else {
            document.getElementById('apellidoAlert').style.display = 'none';
        }

        // Validar correo institucional
        if (correo_institucional.indexOf('@') !== -1) {
            document.getElementById('correoAlert').style.display = 'block';
            valido = false;
        } else {
            document.getElementById('correoAlert').style.display = 'none';
        }

        // Validar teléfono
        if (!/^\d{10}$/.test(telefono)) {
            document.getElementById('telefonoAlert').style.display = 'block';
            valido = false;
        } else {
            document.getElementById('telefonoAlert').style.display = 'none';
        }

        // Validar centro universitario
        if (centro_universitario === "") {
            document.getElementById('centroUniversitarioAlert').style.display = 'block';
            valido = false;
        } else {
            document.getElementById('centroUniversitarioAlert').style.display = 'none';
        }

        return valido;
    }

    function evaluarContrasena(contrasena) {
        var nivel = 0;
        if (contrasena.length >= 8) nivel++;
        if (/[A-Z]/.test(contrasena) && /[a-z]/.test(contrasena)) nivel++;
        if (/[0-9]/.test(contrasena)) nivel++;
        if (/[\W_]/.test(contrasena)) nivel++;
        return nivel;
    }

    function evaluarSeguridadContrasena(contrasena) {
        var nivel = evaluarContrasena(contrasena);
        var barra = document.getElementById('passwordStrengthBar').querySelector('.progress-bar');
        var texto = document.getElementById('passwordStrengthText');
        var porcentaje = 0;
        var color = '';
        var mensaje = '';

        switch (nivel) {
            case 0:
            case 1:
                porcentaje = 25;
                color = 'bg-danger';
                mensaje = 'Muy débil';
                break;
            case 2:
                porcentaje = 50;
                color = 'bg-warning';
                mensaje = 'Débil';
                break;
            case 3:
                porcentaje = 75;
                color = 'bg-info';
                mensaje = 'Moderada';
                break;
            case 4:
                porcentaje = 100;
                color = 'bg-success';
                mensaje = 'Fuerte';
                break;
        }

        barra.style.width = porcentaje + '%';
        barra.className = 'progress-bar ' + color;
        texto.textContent = 'Seguridad de la contraseña: ' + mensaje;
    }

    function validarUsuario(usuario) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'check_usuario.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var respuesta = xhr.responseText;
                if (respuesta === 'existe') {
                    document.getElementById('usuarioExistenteAlert').style.display = 'block';
                    document.getElementById('usuarioAlert').style.display = 'none';
                } else {
                    document.getElementById('usuarioExistenteAlert').style.display = 'none';
                }
            }
        };
        xhr.send('usuario=' + encodeURIComponent(usuario));
    }

    document.getElementById('usuario').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
        validarUsuario(this.value);
    });

    document.getElementById('nombre').addEventListener('input', function() {
        this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '');
    });

    document.getElementById('apellido').addEventListener('input', function() {
        this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '');
    });

    document.getElementById('contrasena').addEventListener('input', function() {
        evaluarSeguridadContrasena(this.value);
    });

    document.getElementById('correo_institucional').addEventListener('input', function() {
        this.value = this.value.toLowerCase().replace(/[^a-z0-9.]/g, '');
    });

    document.getElementById('telefono').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
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
    .input-group-prepend,
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
