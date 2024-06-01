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
        .logout-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .logout-overlay .logout-content {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>

<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

$url = "http://" . $_SERVER['HTTP_HOST'] . "/sitioweb";
?>

<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="#">Administrador</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/administrador/inicio.php">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/administrador/seccion/productos.php">Libros</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Más Opciones
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Usuarios</a>
                    <a class="dropdown-item" href="#">Reportes</a>
                    <a class="dropdown-item" href="#">Estadísticas</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="logout-link">Cerrar Sesión</a>
            </li>
        </ul>
    </div>
</nav>

<div class="logout-overlay" id="logout-overlay">
    <div class="logout-content">
        <h5>¿Está seguro que desea salir de su cuenta?</h5>
        <button class="btn btn-danger" id="confirm-logout">Salir</button>
        <button class="btn btn-secondary" id="cancel-logout">Cancelar</button>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGaS3ukQmTktG8f5DpiUibVx3" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIyFEYeDjAxZw8++PpRtW0uChFfYCAaMSFZcUOLO" crossorigin="anonymous"></script>
<script>
    document.getElementById('logout-link').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('logout-overlay').style.display = 'flex';
    });

    document.getElementById('confirm-logout').addEventListener('click', function() {
        document.getElementById('logout-overlay').innerHTML = '<h5>Saliendo...</h5>';
        setTimeout(function() {
            window.location.href = "<?php echo $url; ?>/administrador/seccion/cerrar.php";
        }, 2000);
    });

    document.getElementById('cancel-logout').addEventListener('click', function() {
        document.getElementById('logout-overlay').style.display = 'none';
    });
</script>

</body>
</html>
