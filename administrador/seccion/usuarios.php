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

include("../template/cabecera_admin.php");
?>

<div class="container mt-5">
    <h1 class="text-center">Gestión de Usuarios</h1>
    <p class="text-center">Aquí puedes gestionar los usuarios registrados en el sitio web.</p>
    <!-- Contenido específico para la gestión de usuarios -->
</div>

<?php include("../template/pie.php"); ?>
