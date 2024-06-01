<?php include("template/cabecera_publica.php"); ?>

<?php
include("administrador/config/bd.php");

$id = isset($_GET['id']) ? $_GET['id'] : "";
$sentenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE id=:id");
$sentenciaSQL->bindParam(':id', $id);
$sentenciaSQL->execute();
$libro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="row">
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
                <a name="" id="" class="btn btn-primary" href="productos.php" role="button">Volver</a>
            </div>
        </div>
    </div>
</div>

<?php include("template/pie.php"); ?>
