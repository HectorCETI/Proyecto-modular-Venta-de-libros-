<?php 
session_start();
if ($_POST) {
    if (($_POST['usuario'] == "admin") && ($_POST['contrasena'] == "admin")) {
        $_SESSION['usuario'] = "OK";
        $_SESSION['nombreUsuario'] = "Administrador";
        header('Location:inicio.php');
    } else {
        $mensaje = "Usuario o contraseña incorrectos";
    }
}
?>

<?php include("../template/cabecera_publica.php"); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-center" style="color: #800000;">Iniciar Sesión</h3>
                    <?php if (isset($mensaje)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php } ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="usuario" style="color: #333;">Usuario:</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese su usuario">
                        </div>
                        <div class="form-group">
                            <label for="contrasena" style="color: #333;">Contraseña:</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña">
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
</style>
</body>
</html>
