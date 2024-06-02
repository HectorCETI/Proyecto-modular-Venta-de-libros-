<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location:../index.php");
    exit;
} else {
    if ($_SESSION['usuario'] == "OK") {
        $nombreUsuario = $_SESSION["nombreUsuario"];
    }
}

include("template/cabecera_admin.php");
?>

<div class="container container-custom mt-5">
    <div class="page-header text-center">
        <h1 class="display-4">Panel de Administración</h1>
        <p class="lead">Bienvenido, <?php echo $nombreUsuario; ?>. Aquí puedes gestionar el contenido de la biblioteca.</p>
    </div>
    <div class="row mt-4">
        <!-- Tarjeta de Estadísticas -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom mb-4 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Estadísticas</h5>
                    <p class="card-text">Consulta y analiza las estadísticas del sitio web para obtener información sobre el rendimiento y el uso.</p>
                    <a href="seccion/estadisticas.php" class="btn btn-primary mt-auto">Ver Estadísticas</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Gestión de Libros -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom mb-4 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Gestión de Libros</h5>
                    <p class="card-text">Añade, edita o elimina libros de la biblioteca. Mantén tu catálogo actualizado y organizado.</p>
                    <a href="seccion/productos.php" class="btn btn-primary mt-auto">Ir a Libros</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Gestión de Usuarios -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom mb-4 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Gestión de Usuarios</h5>
                    <p class="card-text">Administra los usuarios registrados, controla sus permisos y actividades en el sitio web.</p>
                    <a href="seccion/usuarios.php" class="btn btn-primary mt-auto">Ir a Usuarios</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Reportes -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom mb-4 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Reportes</h5>
                    <p class="card-text">Genera y visualiza reportes detallados sobre las actividades y el rendimiento del sitio.</p>
                    <a href="seccion/reportes.php" class="btn btn-primary mt-auto">Ver Reportes</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("template/pie.php"); ?>

<!-- Custom CSS for the Admin Panel -->
<style>
    body {
        background-color: #f3f4f6;
        color: #333;
        font-family: 'Arial', sans-serif;
    }
    .container-custom {
        background-color: #e9ecef;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        margin-top: 100px; /* Ajuste de margen superior */
    }
    .page-header {
        color: #4a4a4a;
        margin-bottom: 30px;
    }
    .card-custom {
        background-color: #ffffff;
        border: none;
        border-radius: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    }
    .card-title {
        font-size: 1.75rem;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 15px;
    }
    .card-text {
        color: #6c757d;
        margin-bottom: 20px;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        font-size: 1rem;
        font-weight: bold;
        transition: background-color 0.2s, border-color 0.2s;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
</style>
