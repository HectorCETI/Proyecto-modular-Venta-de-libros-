<?php
include("../template/cabecera_admin.php");
include("../config/bd.php");

// Consulta para obtener los libros más clickeados
$query_clicks = "
SELECT libros.nombre, COUNT(libro_clicks.libro_id) as clics
FROM libro_clicks
JOIN libros ON libro_clicks.libro_id = libros.id
GROUP BY libro_clicks.libro_id
ORDER BY clics DESC
LIMIT 6
";
$result_clicks = $conexion->query($query_clicks)->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener los libros más buscados por nombre
$query_busqueda_nombre = "
SELECT libros.nombre, COUNT(libro_busquedas.libro_id) as busquedas
FROM libro_busquedas
JOIN libros ON libro_busquedas.libro_id = libros.id
WHERE criterio = 'nombre'
GROUP BY libro_busquedas.libro_id
ORDER BY busquedas DESC
LIMIT 6
";
$result_busqueda_nombre = $conexion->query($query_busqueda_nombre)->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener los libros más buscados por ID
$query_busqueda_id = "
SELECT libros.nombre, COUNT(libro_busquedas.libro_id) as busquedas
FROM libro_busquedas
JOIN libros ON libro_busquedas.libro_id = libros.id
WHERE criterio = 'id'
GROUP BY libro_busquedas.libro_id
ORDER BY busquedas DESC
LIMIT 6
";
$result_busqueda_id = $conexion->query($query_busqueda_id)->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener los rangos de precios más buscados
$query_busqueda_precios = "
SELECT CONCAT('De ', precio_min, ' a ', precio_max) as rango, COUNT(*) as busquedas
FROM libro_busquedas
WHERE criterio = 'precio'
GROUP BY precio_min, precio_max
ORDER BY busquedas DESC
LIMIT 6
";
$result_busqueda_precios = $conexion->query($query_busqueda_precios)->fetchAll(PDO::FETCH_ASSOC);

// Función para generar gráficos con Chart.js
function generarGrafico($elementId, $titulo, $labels, $data, $label) {
    echo "
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('$elementId').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: " . json_encode($labels) . ",
                datasets: [{
                    label: '$label',
                    data: " . json_encode($data) . ",
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: '$titulo'
                    }
                }
            }
        });
    });
    </script>
    ";
}

?>
<div class="container mt-5">
    <h1 class="mb-4 text-center" style="color: #333;">Estadísticas del Bazar de Reciclaje de Libros</h1>

    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <canvas id="graficoClicks"></canvas>
                    <?php
                    $labels = array_column($result_clicks, 'nombre');
                    $data = array_column($result_clicks, 'clics');
                    generarGrafico('graficoClicks', 'Top 6 Libros Más Clickeados', $labels, $data, 'Clics');
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <canvas id="graficoBusquedaNombre"></canvas>
                    <?php
                    $labels = array_column($result_busqueda_nombre, 'nombre');
                    $data = array_column($result_busqueda_nombre, 'busquedas');
                    generarGrafico('graficoBusquedaNombre', 'Top 6 Libros Más Buscados por Nombre', $labels, $data, 'Búsquedas');
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <canvas id="graficoBusquedaID"></canvas>
                    <?php
                    $labels = array_column($result_busqueda_id, 'nombre');
                    $data = array_column($result_busqueda_id, 'busquedas');
                    generarGrafico('graficoBusquedaID', 'Top 6 Libros Más Buscados por ID', $labels, $data, 'Búsquedas');
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <canvas id="graficoBusquedaPrecios"></canvas>
                    <?php
                    $labels = array_column($result_busqueda_precios, 'rango');
                    $data = array_column($result_busqueda_precios, 'busquedas');
                    generarGrafico('graficoBusquedaPrecios', 'Rangos de Precios Más Buscados', $labels, $data, 'Búsquedas');
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("../template/pie.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .card {
        border: none;
        border-radius: 8px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    }
    .card-body {
        padding: 20px;
    }
    h1 {
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        color: #333;
    }
</style>
