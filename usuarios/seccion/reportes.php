<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
} else {
    $nombreUsuario = $_SESSION["usuario"];
}

include("../template/cabecera_usuario.php");
?>

<div class="container mt-5">
    <h1 class="text-center">Reportes</h1>
    <p class="text-center">Aquí puedes visualizar y generar reportes detallados.</p>
    <!-- Contenido específico para la generación de reportes -->
</div>

<?php include("../template/pie.php"); ?>
