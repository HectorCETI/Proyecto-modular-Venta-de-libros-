<?php include("template/cabecera_publica.php"); ?>

<?php
include("administrador/config/bd.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

if ($id > 0) {
    $sentenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE id=:id");
    $sentenciaSQL->bindParam(':id', $id, PDO::PARAM_INT);
    $sentenciaSQL->execute();
    $libro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

    // Registro de click en "ver más"
    if ($libro) {
        $insertClick = $conexion->prepare("INSERT INTO libro_clicks (libro_id, timestamp) VALUES (:libro_id, NOW())");
        $insertClick->bindParam(':libro_id', $id, PDO::PARAM_INT);
        $insertClick->execute();
    }
} else {
    $libro = false;
}
?>

<div class="container mt-4">
    <div class="row">
        <?php if ($libro): ?>
            <div class="col-md-3">
                <div class="card border-0 shadow-lg">
                    <img class="card-img-top" src="img/<?php echo $libro['imagen']; ?>" alt="<?php echo $libro['nombre']; ?>" style="width: 100%; height: auto; border-radius: 8px;">
                </div>
            </div>
            <div class="col-md-9">
                <div class="card-body bg-light border-0 shadow-lg rounded">
                    <h4 class="card-title" style="color: #800000;"><?php echo $libro['nombre']; ?></h4>
                    <p class="card-text" style="color: #333;"><?php echo $libro['descripcion']; ?></p>
                    <p class="card-text" style="color: #333;"><strong>Precio:</strong> $<?php echo $libro['precio']; ?></p>
                    <a name="" id="" class="btn btn-primary" href="productos.php?page=<?php echo $page; ?>" role="button" style="background-color: #800000; border-color: #800000;">Volver</a>
                </div>
            </div>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    Libro no encontrado.
                </div>
                <a name="" id="" class="btn btn-primary" href="productos.php?page=<?php echo $page; ?>" role="button" style="background-color: #800000; border-color: #800000;">Volver</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include("template/pie.php"); ?>

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
        padding: 20px;
    }
    h4, p {
        font-family: 'Arial', sans-serif;
    }
    h4 {
        font-size: 1.5rem;
        color: #800000;
    }
    p {
        font-size: 1rem;
        color: #333;
    }
    .btn {
        font-family: 'Arial', sans-serif;
        font-size: 1rem;
        font-weight: bold;
    }
    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
    }
</style>
