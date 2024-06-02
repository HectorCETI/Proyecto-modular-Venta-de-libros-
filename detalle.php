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

<div class="container">
    <div class="row">
        <?php if ($libro): ?>
            <div class="col-md-3"> <!-- Reducir el tamaño de la imagen a un 30% del tamaño total -->
                <div class="card">
                    <img class="card-img-top" src="img/<?php echo $libro['imagen']; ?>" style="width: 100%; height: auto;" alt="">
                </div>
            </div>
            <div class="col-md-9"> <!-- Ajustar el tamaño del contenedor del texto -->
                <div class="card-body">
                    <h4 class="card-title"><?php echo $libro['nombre']; ?></h4>
                    <p class="card-text"><?php echo $libro['descripcion']; ?></p>
                    <p class="card-text"><strong>Precio:</strong> $<?php echo $libro['precio']; ?></p>
                    <a name="" id="" class="btn btn-primary" href="productos.php?page=<?php echo $page; ?>" role="button">Volver</a>
                </div>
            </div>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    Libro no encontrado.
                </div>
                <a name="" id="" class="btn btn-primary" href="productos.php?page=<?php echo $page; ?>" role="button">Volver</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include("template/pie.php"); ?>
