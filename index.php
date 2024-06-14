<?php include("template/cabecera_publica.php"); ?>

<?php
include("administrador/config/bd.php");

// Obtener los últimos 4 productos agregados
$sentenciaSQL = $conexion->prepare("SELECT * FROM libros ORDER BY id DESC LIMIT 4");
$sentenciaSQL->execute();
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC); // Muestra los últimos 4 datos dentro de la DBs
?>

<style>
    body {
        background-color: #f7f7f7; /* Gris claro */
    }
    .card {
        border: none;
        border-radius: 8px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    }
    .card-title, .card-text {
        font-family: 'Arial', sans-serif;
        color: #333;
    }
    .card-header {
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        font-size: 1.25rem;
        background-color: #800000;
        color: #ffffff;
        border-radius: 8px 8px 0 0;
    }
    .btn-primary {
        background-color: #800000;
        border-color: #800000;
        font-family: 'Arial', sans-serif;
    }
    .btn-primary:hover {
        background-color: #ffcd00;
        border-color: #ffcd00;
        color: #333;
    }
    h1, h2, h3, p {
        font-family: 'Arial', sans-serif;
    }
    h1 {
        color: #800000;
    }
    h2, h3 {
        color: #800000;
    }
    p {
        color: #333;
    }
</style>

<div class="container-fluid p-0">
    <div class="container mt-4">
        <!-- Imagen de presentación -->
        <div class="mb-5">
            <img src="img/banner1.jpg" class="d-block w-100" style="height: 300px; object-fit: cover; object-position: center;" alt="Imagen de presentación">
        </div>

        <!-- Sección de bienvenida -->
        <section class="mb-5 text-center">
            <h1 class="display-4">Bienvenidos a UniBooks UDG</h1>
            <p class="lead">Tu plataforma para reciclar, regalar y encontrar libros usados para estudiantes.</p>
            <hr class="my-4" style="border-color: #ffcd00;">
            <p>En UniBooks UDG, creemos en el poder de los libros para inspirar, educar y entretener. Nos dedicamos a ofrecer una selección diversa y emocionante de libros para todos los gustos y edades.</p>
        </section>

        <!-- Productos Recientes -->
        <h2 class="mb-4">Libros Recientes</h2>
        <div class="row">
            <?php foreach($listaLibros as $libro) { ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 d-flex flex-column">
                        <img class="card-img-top" src="img/<?php echo $libro['imagen']; ?>" style="height: 17rem; object-fit: cover;" alt="">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $libro['nombre']; ?></h5>
                            <p class="card-text"><strong>ID:</strong> <?php echo $libro['id']; ?></p>
                            <p class="card-text" style="color: #333; font-size: 0.9rem;"><?php echo (strlen($libro['descripcion']) > 50) ? substr($libro['descripcion'], 0, 50) . '...' : $libro['descripcion']; ?></p>
                            <div class="mt-auto">
                                <p class="card-text mt-2">
                                    <?php if ($libro['precio'] != 0) { ?>
                                        <span style="color: #333; font-weight: bold; font-size: 1.5em;">$<?php echo number_format($libro['precio'], 2); ?></span>
                                    <?php } else { ?>
                                        <span style="color: green; font-weight: bold; font-size: 1.5em;">Gratis</span>
                                    <?php } ?>
                                </p>
                                <a name="" id="" class="btn btn-primary mt-auto" href="detalle.php?id=<?php echo $libro['id']; ?>" role="button">Ver más</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Sección de información adicional -->
        <section class="mt-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100" onclick="location.href='sobre_nosotros.php'" style="cursor: pointer;">
                        <div class="card-body text-center">
                            <h3>Misión</h3>
                            <p>Fomentar el reciclaje de libros usados, facilitando el acceso a recursos educativos asequibles para los estudiantes de la Universidad de Guadalajara.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100" onclick="location.href='sobre_nosotros.php'" style="cursor: pointer;">
                        <div class="card-body text-center">
                            <h3>Visión</h3>
                            <p>Convertirnos en la plataforma líder de intercambio y reciclaje de libros universitarios, promoviendo la sostenibilidad y el aprendizaje continuo.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100" onclick="location.href='sobre_nosotros.php'" style="cursor: pointer;">
                        <div class="card-body text-center">
                            <h3>Valores</h3>
                            <p>Compromiso, sostenibilidad y educación accesible. Creemos en el poder de compartir conocimientos y recursos para un futuro mejor.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php include("template/pie.php"); ?>
