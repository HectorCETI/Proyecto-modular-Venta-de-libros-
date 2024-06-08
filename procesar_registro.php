<?php
include("config/bd.php");

if ($_POST) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    // Verificar si el usuario ya existe
    $sentenciaSQL = $conexion->prepare("SELECT * FROM usuarios WHERE usuario=:usuario");
    $sentenciaSQL->bindParam(':usuario', $usuario);
    $sentenciaSQL->execute();
    $resultado = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        $mensaje = "El nombre de usuario ya está en uso. Por favor, elige otro.";
    } else {
        // Cifrar la contraseña
        $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario en la base de datos
        $sentenciaSQL = $conexion->prepare("INSERT INTO usuarios (usuario, contrasena, nombre, email) VALUES (:usuario, :contrasena, :nombre, :email)");
        $sentenciaSQL->bindParam(':usuario', $usuario);
        $sentenciaSQL->bindParam(':contrasena', $hashedPassword);
        $sentenciaSQL->bindParam(':nombre', $nombre);
        $sentenciaSQL->bindParam(':email', $email);
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
                            <label for="email" style="color: #333;">Correo Electrónico:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese su correo electrónico">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" style="background-color: #800000; border-color: #800000;">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("template/pie.php"); ?>
