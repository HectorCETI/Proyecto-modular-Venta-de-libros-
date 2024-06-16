<?php
include("template/cabecera_publica.php");
include("administrador/config/bd.php");

$mensaje = "";
$usuario = isset($_SESSION['usuarioPendiente']) ? $_SESSION['usuarioPendiente'] : '';

if ($_POST) {
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
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg">
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
        </div>
    </div>
</div>

<?php include("template/pie.php"); ?>
