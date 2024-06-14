<?php
include("template/cabecera_publica.php");
include("administrador/config/bd.php");

$mensaje = "";

if ($_POST) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $nombre = strtoupper($_POST['nombre']);
    $apellido = strtoupper($_POST['apellido']);
    $correo_institucional = $_POST['correo_institucional'] . '@alumnos.udg.mx';
    $telefono = $_POST['telefono'];
    $centro_universitario = $_POST['centro_universitario'];

    // Verificar si el nombre de usuario ya existe
    $verificarUsuario = $conexion->prepare("SELECT * FROM usuarios WHERE usuario=:usuario");
    $verificarUsuario->bindParam(':usuario', $usuario);
    $verificarUsuario->execute();

    if (!preg_match('/^[a-zA-Z0-9_]{5,12}$/', $usuario)) {
        $mensaje = "El nombre de usuario debe tener entre 5 y 12 caracteres, y solo puede contener letras, números y guiones bajos.";
    } elseif ($verificarUsuario->fetch(PDO::FETCH_ASSOC)) {
        $mensaje = "El nombre de usuario ya está en uso.";
    } elseif (!preg_match('/^\d{10}$/', $telefono)) {
        $mensaje = "El número de teléfono debe tener 10 dígitos.";
    } elseif (!preg_match('/@alumnos.udg.mx$/', $correo_institucional)) {
        $mensaje = "El correo institucional debe terminar con '@alumnos.udg.mx'.";
    } else {
        // Cifrar la contraseña
        $contrasenaCifrada = password_hash($contrasena, PASSWORD_BCRYPT);

        // Insertar el nuevo usuario en la base de datos
        $sentenciaSQL = $conexion->prepare("INSERT INTO usuarios (usuario, contrasena, nombre, apellido, correo_institucional, telefono, centro_universitario) VALUES (:usuario, :contrasena, :nombre, :apellido, :correo_institucional, :telefono, :centro_universitario)");
        $sentenciaSQL->bindParam(':usuario', $usuario);
        $sentenciaSQL->bindParam(':contrasena', $contrasenaCifrada);
        $sentenciaSQL->bindParam(':nombre', $nombre);
        $sentenciaSQL->bindParam(':apellido', $apellido);
        $sentenciaSQL->bindParam(':correo_institucional', $correo_institucional);
        $sentenciaSQL->bindParam(':telefono', $telefono);
        $sentenciaSQL->bindParam(':centro_universitario', $centro_universitario);
        $sentenciaSQL->execute();

        $mensaje = "Registro exitoso. Ahora puedes iniciar sesión.";
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
                            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese su usuario" required>
                            <small id="usuarioAlert" class="form-text text-danger" style="display: none;">El nombre de usuario debe tener entre 5 y 12 caracteres, y solo puede contener letras, números y guiones bajos.</small>
                        </div>
                        <div class="form-group">
                            <label for="contrasena" style="color: #333;">Contraseña:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <small id="contrasenaAlert" class="form-text text-danger" style="display: none;">La contraseña debe tener al menos 8 caracteres y cumplir con los requisitos de seguridad.</small>
                        </div>
                        <div class="form-group">
                            <label for="nombre" style="color: #333;">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre" required>
                            <small id="nombreAlert" class="form-text text-danger" style="display: none;">El nombre solo puede contener letras.</small>
                        </div>
                        <div class="form-group">
                            <label for="apellido" style="color: #333;">Apellido:</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese su apellido" required>
                            <small id="apellidoAlert" class="form-text text-danger" style="display: none;">El apellido solo puede contener letras.</small>
                        </div>
                        <div class="form-group">
                            <label for="correo_institucional" style="color: #333;">Correo Institucional:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="correo_institucional" name="correo_institucional" placeholder="Ingrese su correo institucional" required style="flex: 1;">
                                <div class="input-group-append">
                                    <span class="input-group-text">@alumnos.udg.mx</span>
                                </div>
                            </div>
                            <small id="correoAlert" class="form-text text-danger" style="display: none;">Por favor, ingresa solo la parte anterior al '@' de tu correo institucional válido de la Universidad de Guadalajara. Esta plataforma está destinada únicamente para estudiantes de la UDG.</small>
                        </div>
                        <div class="form-group">
                            <label for="telefono" style="color: #333;">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese su teléfono" required>
                            <small id="telefonoAlert" class="form-text text-danger" style="display: none;">El número de teléfono debe tener 10 dígitos.</small>
                        </div>
                        <div class="form-group">
                            <label for="centro_universitario" style="color: #333;">Centro Universitario:</label>
                            <select class="form-control" id="centro_universitario" name="centro_universitario" required>
                                <option value="">Seleccione su centro universitario</option>
                                <option value="CUCEI">CUCEI - Centro Universitario de Ciencias Exactas e Ingenierías</option>
                                <option value="CUCEA">CUCEA - Centro Universitario de Ciencias Económico Administrativas</option>
                            </select>
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
        var icon = this.querySelector('i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    function validarFormulario() {
        var usuario = document.getElementById('usuario').value;
        var contrasena = document.getElementById('contrasena').value;
        var nombre = document.getElementById('nombre').value;
        var apellido = document.getElementById('apellido').value;
        var correo_institucional = document.getElementById('correo_institucional').value;
        var telefono = document.getElementById('telefono').value;
        var valido = true;

        // Validar usuario
        if (!/^[a-zA-Z0-9_]{5,12}$/.test(usuario)) {
            document.getElementById('usuarioAlert').style.display = 'block';
            valido = false;
        } else {
            document.getElementById('usuarioAlert').style.display = 'none';
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
        if (!/^[a-zA-Z]+$/.test(nombre)) {
            document.getElementById('nombreAlert').style.display = 'block';
            valido = false;
        } else {
            document.getElementById('nombreAlert').style.display = 'none';
        }

        if (!/^[a-zA-Z]+$/.test(apellido)) {
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
</script>

<style>
    .card {
        border-radius: 8px;
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
        font-family: 'Arial', sans-serif;
        font-size: 1rem;
        font-weight: bold;
    }
    .input-group {
        display: flex;
        flex-direction: row;
        align-items: center;
    }
    .input-group-append {
        height: 100%;
    }
    .input-group-text {
        background-color: #e9ecef;
        border-left: none;
    }
    .fa {
        font-size: 1.2rem;
    }
</style>
</body>
</html>
