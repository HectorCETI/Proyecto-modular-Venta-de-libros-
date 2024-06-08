<?php
include("template/cabecera_publica.php");
include("administrador/config/bd.php");

$mensaje = "";

if ($_POST) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    // Verificar si el nombre de usuario ya existe
    $verificarUsuario = $conexion->prepare("SELECT * FROM usuarios WHERE usuario=:usuario");
    $verificarUsuario->bindParam(':usuario', $usuario);
    $verificarUsuario->execute();

    if ($verificarUsuario->fetch(PDO::FETCH_ASSOC)) {
        $mensaje = "El nombre de usuario ya está en uso.";
    } else {
        // Cifrar la contraseña
        $contrasenaCifrada = password_hash($contrasena, PASSWORD_BCRYPT);

        // Insertar el nuevo usuario en la base de datos
        $sentenciaSQL = $conexion->prepare("INSERT INTO usuarios (usuario, contrasena, nombre, email) VALUES (:usuario, :contrasena, :nombre, :email)");
        $sentenciaSQL->bindParam(':usuario', $usuario);
        $sentenciaSQL->bindParam(':contrasena', $contrasenaCifrada);
        $sentenciaSQL->bindParam(':nombre', $nombre);
        $sentenciaSQL->bindParam(':email', $email);
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
                    <form method="POST">
                        <div class="form-group">
                            <label for="usuario" style="color: #333;">Usuario:</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese su usuario" required>
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
                        </div>
                        <div class="form-group">
                            <label for="nombre" style="color: #333;">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="email" style="color: #333;">Correo Electrónico:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese su correo electrónico" required>
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
        position: relative;
    }
    .input-group-append {
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
    }
    .fa {
        font-size: 1.2rem;
    }
</style>
</body>
</html>
