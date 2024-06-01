<!doctype html>
<html lang="es">
<head>
    <title>Contacto - Biblioteca</title>
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
        .header-section {
            background-color: #4a148c;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
            margin-top: 20px; /* Separar el header del menú */
        }
        .header-section h1 {
            font-size: 2.5rem;
        }
        .content-section {
            padding: 40px 0;
            background-color: #f8f9fa;
        }
        .content-section h2 {
            color: #6f42c1;
            margin-bottom: 20px;
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
                <a class="nav-link" href="<?php echo $url; ?>/sobre_nosotros.php">Sobre Nosotros</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/contacto.php">Contacto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/administrador/index.php">Login</a>
            </li>
        </ul>
    </div>
</nav>

<div class="header-section">
    <h1>Contacto</h1>
</div>

<div class="container content-section">
    <div class="row">
        <div class="col-md-6">
            <h2>Contáctanos</h2>
            <p>Si tienes alguna pregunta o comentario, no dudes en ponerte en contacto con nosotros. Puedes utilizar el formulario a continuación o enviarnos un correo electrónico.</p>
            <form>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" placeholder="Tu nombre">
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" placeholder="Tu correo electrónico">
                </div>
                <div class="form-group">
                    <label for="mensaje">Mensaje</label>
                    <textarea class="form-control" id="mensaje" rows="5" placeholder="Tu mensaje"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
        <div class="col-md-6">
            <h2>Información de Contacto</h2>
            <p><strong>Dirección:</strong> Calle Falsa 123, Ciudad, País</p>
            <p><strong>Teléfono:</strong> +123 456 7890</p>
            <p><strong>Email:</strong> contacto@biblioteca.com</p>
            <p>También puedes encontrarnos en nuestras redes sociales:</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#"><img src="img/facebook_icon.png" alt="Facebook" style="width: 30px;"></a></li>
                <li class="list-inline-item"><a href="#"><img src="img/twitter_icon.png" alt="Twitter" style="width: 30px;"></a></li>
                <li class="list-inline-item"><a href="#"><img src="img/instagram_icon.png" alt="Instagram" style="width: 30px;"></a></li>
            </ul>
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
