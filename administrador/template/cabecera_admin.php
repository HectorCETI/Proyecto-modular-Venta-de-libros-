<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location:../index.php");
    exit;
} else {
    if ($_SESSION['usuario'] == "OK") {
        $nombreUsuario = $_SESSION["nombreUsuario"];
    }
}
?>

<!doctype html>
<html lang="es">
<head>
    <title>Administrador - Bazar de Reciclaje de Libros</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .navbar-admin {
            background-color: #2c3e50;
        }
        .navbar-admin .navbar-brand,
        .navbar-admin .nav-link {
            color: #ecf0f1;
        }
        .navbar-admin .nav-link:hover {
            color: #bdc3c7;
        }
        .navbar-brand-admin {
            font-weight: bold;
            color: #f39c12;
            font-size: 1.5rem;
        }
        .dropdown-menu {
            animation: fadeIn 0.5s;
            z-index: 1050; /* Ensure dropdown menu is above other content */
            background-color: #34495e; /* Color de fondo más suave */
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .dropdown-item {
            color: #ecf0f1;
            transition: background-color 0.3s, color 0.3s;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1); /* Línea suave entre opciones */
        }
        .dropdown-item:last-child {
            border-bottom: none; /* Eliminar la línea de la última opción */
        }
        .dropdown-item:hover {
            background-color: #f39c12;
            color: #2c3e50;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
        body {
            background-color: #e9ecef; /* Fondo gris claro para todo el cuerpo */
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

<?php $url = "http://" . $_SERVER['HTTP_HOST'] . "/sitioweb" ?>

<nav class="navbar navbar-expand-lg navbar-admin">
    <a class="navbar-brand navbar-brand-admin" href="#">Administrador</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/administrador/inicio.php">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/administrador/seccion/productos.php">Bazar de Libros</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                    Más Opciones
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="<?php echo $url; ?>/administrador/seccion/estadisticas.php">Estadísticas</a></li>
                    <li><a class="dropdown-item" href="<?php echo $url; ?>/administrador/seccion/usuarios.php">Gestión de Usuarios</a></li>
                    <li><a class="dropdown-item" href="<?php echo $url; ?>/administrador/seccion/reportes.php">Reportes</a></li>
                </ul>
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

    // Ensure the dropdown works properly
    $(document).ready(function() {
        $('.dropdown-toggle').dropdown();
    });

    // Ensure the dropdown is always on top
    $('.dropdown-toggle').on('click', function () {
        var $el = $(this).next('.dropdown-menu');
        var isVisible = $el.is(':visible');
        $('.dropdown-menu').hide();
        if (!isVisible) {
            $el.show();
        }
    });

    // Close the dropdown if clicking outside of it
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.dropdown-menu, .dropdown-toggle').length) {
            $('.dropdown-menu').hide();
        }
    });
</script>
</body>
</html>
