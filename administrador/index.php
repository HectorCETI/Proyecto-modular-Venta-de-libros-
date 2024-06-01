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

<!doctype html>
<html lang="es">
<head>
    <title>Administrador - Biblioteca</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .navbar-custom {
            background-color: #6f42c1;
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #fff;
        }
        .navbar-custom .nav-link:hover {
            color: #d4b6ff;
        }
        .container-custom {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .card-custom {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<?php $url = "http://" . $_SERVER['HTTP_HOST'] . "/sitioweb" ?>

<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="#">Biblioteca</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/index.php">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/productos.php">Galería de Libros</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/nosotros.php">Sobre Nosotros</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/contacto.php">Contacto</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container container-custom">
    <div class="row">
        <div class="col-md-4 mx-auto">
            <div class="card card-custom">
                <div class="card-body">
                    <h3 class="card-title text-center">Iniciar Sesión</h3>
                    <?php if (isset($mensaje)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php } ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="usuario">Usuario:</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese su usuario">
                        </div>
                        <div class="form-group">
                            <label for="contrasena">Contraseña:</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGaS3ukQmTktG8f5DpiUibVx3" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIyFEYeDjAxZw8++PpRtW0uChFfYCAaMSFZcUOLO" crossorigin="anonymous"></script>
</body>
</html>
