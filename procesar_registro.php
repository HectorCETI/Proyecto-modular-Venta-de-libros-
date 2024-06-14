<?php
include("config/bd.php");

if ($_POST) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo_institucional = $_POST['correo_institucional'] . '@alumnos.udg.mx';
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $codigo_postal = $_POST['codigo_postal'];

    // Verificar si el usuario ya existe
    $sentenciaSQL = $conexion->prepare("SELECT * FROM usuarios WHERE usuario=:usuario");
    $sentenciaSQL->bindParam(':usuario', $usuario);
    $sentenciaSQL->execute();
    $resultado = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        $mensaje = "El nombre de usuario ya está en uso. Por favor, elige otro.";
    } elseif (!preg_match('/@alumnos.udg.mx$/', $correo_institucional)) {
        $mensaje = "El correo institucional debe terminar con '@alumnos.udg.mx'.";
    } elseif (!preg_match('/^\d{10}$/', $telefono)) {
        $mensaje = "El número de teléfono debe tener 10 dígitos.";
    } elseif (!preg_match('/^\d{5}$/', $codigo_postal)) {
        $mensaje = "El código postal debe tener 5 dígitos.";
    } else {
        // Cifrar la contraseña
        $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario en la base de datos
        $sentenciaSQL = $conexion->prepare("INSERT INTO usuarios (usuario, contrasena, nombre, apellido, correo_institucional, telefono, direccion, codigo_postal) VALUES (:usuario, :contrasena, :nombre, :apellido, :correo_institucional, :telefono, :direccion, :codigo_postal)");
        $sentenciaSQL->bindParam(':usuario', $usuario);
        $sentenciaSQL->bindParam(':contrasena', $hashedPassword);
        $sentenciaSQL->bindParam(':nombre', $nombre);
        $sentenciaSQL->bindParam(':apellido', $apellido);
        $sentenciaSQL->bindParam(':correo_institucional', $correo_institucional);
        $sentenciaSQL->bindParam(':telefono', $telefono);
        $sentenciaSQL->bindParam(':direccion', $direccion);
        $sentenciaSQL->bindParam(':codigo_postal', $codigo_postal);
        $sentenciaSQL->execute();

        // Inicio de sesión automático después del registro
        session_start();
        $_SESSION['usuario'] = $usuario;
        header("Location: usuarios/index.php");
        exit;
    }
}
?>

<?php include("template/cabecera_publica.php"); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-center" style="color: #800000;">Registro de Usuario</h3>
                    <?php if (isset($mensaje)) { ?>
                        <div class="alert alert-danger" role="alert">
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
                            <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre" style="color: #333;">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre">
                        </div>
                        <div class="form-group">
                            <label for="apellido" style="color: #333;">Apellido:</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese su apellido">
                        </div>
                        <div class="form-group">
                            <label for="correo_institucional" style="color: #333;">Correo Institucional:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="correo_institucional" name="correo_institucional" placeholder="Ingrese su correo institucional">
                                <div class="input-group-append">
                                    <span class="input-group-text">@alumnos.udg.mx</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telefono" style="color: #333;">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese su teléfono">
                        </div>
                        <div class="form-group">
                            <label for="direccion" style="color: #333;">Dirección:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ingrese su dirección">
                        </div>
                        <div class="form-group">
                            <label for="codigo_postal" style="color: #333;">Código Postal:</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" placeholder="Ingrese su código postal">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" style="background-color: #800000; border-color: #800000;">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("template/pie.php"); ?>
