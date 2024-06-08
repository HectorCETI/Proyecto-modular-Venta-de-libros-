<!doctype html>
<html lang="es">
<head>
    <title>Bazar de Reciclaje de Libros - Universidad de Guadalajara</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .navbar-custom {
            background-color: #800000; /* Color tinto sobrio */
            transition: background-color 0.3s;
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #fff;
            transition: color 0.3s;
        }
        .navbar-custom .navbar-brand:hover,
        .navbar-custom .nav-link:hover {
            color: #ffcd00;
        }
        .navbar-custom .nav-link {
            padding-left: 15px;
            padding-right: 15px;
        }
        .navbar-custom .navbar-toggler {
            border-color: #ffcd00;
        }
        .navbar-custom .navbar-toggler-icon {
            background-color: #ffcd00;
        }
        .dropdown-menu {
            background-color: #800000;
            border: none;
        }
        .dropdown-item {
            color: #fff;
            transition: background-color 0.3s;
        }
        .dropdown-item:hover {
            background-color: #ffcd00;
            color: #333;
        }
        .container-custom {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #333;
            border-color: #333;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #ffcd00;
            border-color: #ffcd00;
            color: #333;
            transform: scale(1.05);
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
        .card-title {
            font-size: 1.5rem;
            color: #800000;
        }
        .card-text {
            color: #333;
        }
        .navbar-nav .nav-item {
            position: relative;
        }
        .navbar-nav .nav-item::after {
            content: '';
            display: block;
            width: 0;
            height: 2px;
            background: #ffcd00;
            transition: width .3s;
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }
        .navbar-nav .nav-item:hover::after {
            width: 100%;
        }
    </style>
</head>
<body>

<?php $url = "http://" . $_SERVER['HTTP_HOST'] . "/sitioweb" ?>

<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="#">Bazar UDG</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/index.php">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/productos.php">Bazar de Libros</a>
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
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/registro.php">Registro</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <br/>
    <div class="row">
