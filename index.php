<?php include("template/cabecera_publica.php"); ?>

<?php
include("administrador/config/bd.php");

// Obtener los últimos 4 productos agregados
$sentenciaSQL = $conexion->prepare("SELECT * FROM libros ORDER BY id DESC LIMIT 4");
$sentenciaSQL->execute();
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC); // Muestra los últimos 4 datos dentro de la DBs
?>

<div class="container mt-4">
    <!-- Banner -->
    <div id="bannerCarousel" class="carousel slide mb-5" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="img/banner1.jpg" class="d-block w-100" style="height: 300px; object-fit: contain; object-position: center;" alt="Banner 1">
            </div>
            <div class="carousel-item">
                <img src="img/banner2.jpg" class="d-block w-100" style="height: 300px; object-fit: contain; object-position: center;" alt="Banner 2">
            </div>
            <div class="carousel-item">
                <img src="img/banner3.jpg" class="d-block w-100" style="height: 300px; object-fit: contain; object-position: center;" alt="Banner 3">
            </div>
        </div>
        <a class="carousel-control-prev" href="#bannerCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#bannerCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- Sección de bienvenida -->
    <section class="mb-5 text-center">
        <h1 class="display-4">Bienvenidos a Nuestra Biblioteca</h1>
        <p class="lead">Descubre una amplia variedad de libros de todos los géneros y autores. Explora nuestras colecciones y encuentra tu próxima gran lectura.</p>
        <hr class="my-4">
        <p>En nuestra biblioteca, creemos en el poder de los libros para inspirar, educar y entretener. Estamos dedicados a ofrecer una selección diversa y emocionante de libros para todos los gustos y edades.</p>
    </section>

    <!-- Productos Recientes -->
    <h2 class="mb-4">Productos Recientes</h2>
    <div class="row">
        <?php foreach($listaLibros as $libro) { 
            // Calcular precio inflado y descuento
            if ($libro['descuento'] == 0 && $libro['precio'] != 0) {
                $libro['descuento'] = rand(20, 60);
                $updateDiscount = $conexion->prepare("UPDATE libros SET descuento=:descuento WHERE id=:id");
                $updateDiscount->bindParam(':descuento', $libro['descuento']);
                $updateDiscount->bindParam(':id', $libro['id']);
                $updateDiscount->execute();
            }
            if ($libro['precio'] != 0) {
                $inflatedPrice = $libro['precio'] / ((100 - $libro['descuento']) / 100);
            } else {
                $inflatedPrice = 0;
                $libro['descuento'] = 0;
            }
            ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 d-flex flex-column">
                    <img class="card-img-top" src="img/<?php echo $libro['imagen']; ?>" style="height: 17rem;" alt="">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $libro['nombre']; ?></h5>
                        <p class="card-text"><strong>ID:</strong> <?php echo $libro['id']; ?></p>
                        <p class="card-text mt-2">
                            <?php if ($libro['precio'] != 0) { ?>
                                <span style="text-decoration: line-through; color: red; font-size: 1.2em;">$<?php echo number_format($inflatedPrice, 2); ?></span>
                                <span style="color: green; font-weight: bold; font-size: 1.5em;">$<?php echo number_format($libro['precio'], 2); ?></span>
                                <span style="color: gray; font-size: 0.9em;">(<?php echo round(($inflatedPrice - $libro['precio']) / $inflatedPrice * 100); ?>% de descuento)</span>
                            <?php } else { ?>
                                <span style="color: green; font-weight: bold; font-size: 1.5em;">Gratis</span>
                            <?php } ?>
                        </p>
                        <a name="" id="" class="btn btn-primary mt-auto" href="detalle.php?id=<?php echo $libro['id']; ?>" role="button">Ver más</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Sección de información adicional -->
    <section class="mt-5">
        <div class="row">
            <div class="col-md-4">
                <h3>Misión</h3>
                <p>Nuestra misión es fomentar el amor por la lectura y proporcionar un acceso fácil a libros de alta calidad para todos.</p>
            </div>
            <div class="col-md-4">
                <h3>Visión</h3>
                <p>Aspiramos a ser la biblioteca de referencia en nuestra comunidad, ofreciendo recursos valiosos y un servicio excepcional a nuestros usuarios.</p>
            </div>
            <div class="col-md-4">
                <h3>Valores</h3>
                <p>Nos comprometemos con la excelencia, la inclusividad y la innovación en todos nuestros servicios y colecciones.</p>
            </div>
        </div>
    </section>
</div>

<?php include("template/pie.php"); ?>
