<?php include("template/cabecera_publica.php"); ?>
<?php
include("administrador/config/bd.php");

// Parámetros de búsqueda y paginación
$letra = isset($_GET['letra']) ? $_GET['letra'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 10;
$offset = ($page - 1) * $itemsPerPage;

// Construcción de la consulta con filtros y orden
$query = "SELECT * FROM libros WHERE 1=1";
$params = [];

if (!empty($_GET['nombre'])) {
    $query .= " AND nombre LIKE :nombre";
    $params[':nombre'] = "%" . $_GET['nombre'] . "%";
}

if (!empty($_GET['id'])) {
    $query .= " AND id = :id";
    $params[':id'] = $_GET['id'];
}

if (!empty($_GET['precio_min'])) {
    $query .= " AND precio >= :precio_min";
    $params[':precio_min'] = $_GET['precio_min'];
}

if (!empty($_GET['precio_max'])) {
    $query .= " AND precio <= :precio_max";
    $params[':precio_max'] = $_GET['precio_max'];
}

if (!empty($letra)) {
    $query .= " AND nombre LIKE :letra";
    $params[':letra'] = $letra . '%';
}

$query .= " ORDER BY nombre ASC LIMIT :offset, :itemsPerPage";
$params[':offset'] = $offset;
$params[':itemsPerPage'] = $itemsPerPage;

$sentenciaSQL = $conexion->prepare($query);
foreach ($params as $key => &$val) {
    if ($key === ':offset' || $key === ':itemsPerPage') {
        $sentenciaSQL->bindValue($key, $val, PDO::PARAM_INT);
    } else {
        $sentenciaSQL->bindValue($key, $val);
    }
}
$sentenciaSQL->execute();
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

// Registro de búsqueda por nombre
if (!empty($_GET['nombre'])) {
    foreach ($listaLibros as $libro) {
        $insertQuery = $conexion->prepare("INSERT INTO libro_busquedas (libro_id, criterio, fecha) VALUES (:libro_id, 'nombre', NOW())");
        $insertQuery->bindParam(':libro_id', $libro['id']);
        $insertQuery->execute();
    }
}

// Registro de búsqueda por ID
if (!empty($_GET['id'])) {
    foreach ($listaLibros as $libro) {
        $insertQuery = $conexion->prepare("INSERT INTO libro_busquedas (libro_id, criterio, fecha) VALUES (:libro_id, 'id', NOW())");
        $insertQuery->bindParam(':libro_id', $libro['id']);
        $insertQuery->execute();
    }
}

// Registro de búsqueda por rango de precios
if (!empty($_GET['precio_min']) || !empty($_GET['precio_max'])) {
    foreach ($listaLibros as $libro) {
        $insertQuery = $conexion->prepare("INSERT INTO libro_busquedas (libro_id, criterio, precio_min, precio_max, fecha) VALUES (:libro_id, 'precio', :precio_min, :precio_max, NOW())");
        $insertQuery->bindParam(':libro_id', $libro['id']);
        $insertQuery->bindParam(':precio_min', $_GET['precio_min']);
        $insertQuery->bindParam(':precio_max', $_GET['precio_max']);
        $insertQuery->execute();
    }
}

// Obtener el número total de libros para la paginación
$countQuery = "SELECT COUNT(*) as total FROM libros WHERE 1=1";
$countParams = [];

if (!empty($_GET['nombre'])) {
    $countQuery .= " AND nombre LIKE :nombre";
    $countParams[':nombre'] = "%" . $_GET['nombre'] . "%";
}

if (!empty($_GET['id'])) {
    $countQuery .= " AND id = :id";
    $countParams[':id'] = $_GET['id'];
}

if (!empty($_GET['precio_min'])) {
    $countQuery .= " AND precio >= :precio_min";
    $countParams[':precio_min'] = $_GET['precio_min'];
}

if (!empty($_GET['precio_max'])) {
    $countQuery .= " AND precio <= :precio_max";
    $countParams[':precio_max'] = $_GET['precio_max'];
}

if (!empty($letra)) {
    $countQuery .= " AND nombre LIKE :letra";
    $countParams[':letra'] = $letra . '%';
}

$countStmt = $conexion->prepare($countQuery);
foreach ($countParams as $key => &$val) {
    $countStmt->bindValue($key, $val);
}
$countStmt->execute();
$totalItems = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalItems / $itemsPerPage);
?>

<div class="container mt-4">
    <!-- Formulario de Búsqueda -->
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control" name="nombre" placeholder="Buscar por nombre" value="<?php echo isset($_GET['nombre']) ? $_GET['nombre'] : ''; ?>">
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" name="id" placeholder="Buscar por ID" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="precio_min" placeholder="Precio mínimo" step="0.01" value="<?php echo isset($_GET['precio_min']) ? $_GET['precio_min'] : ''; ?>">
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="precio_max" placeholder="Precio máximo" step="0.01" value="<?php echo isset($_GET['precio_max']) ? $_GET['precio_max'] : ''; ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary" name="buscar" style="background-color: #800000; border-color: #800000;">Buscar</button>
            </div>
        </div>
    </form>

    <!-- Filtro por Letra -->
    <div class="mb-4 text-center">
        <?php foreach (range('A', 'Z') as $char) { ?>
            <a href="?letra=<?php echo $char; ?>" class="btn btn-outline-secondary btn-sm"><?php echo $char; ?></a>
        <?php } ?>
        <a href="productos.php" class="btn btn-outline-secondary btn-sm">Todos</a>
    </div>

    <!-- Lista de Libros -->
    <div class="row">
        <?php foreach($listaLibros as $libro) { ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 d-flex flex-column border-0 shadow-lg">
                    <img class="card-img-top" src="./img/<?php echo $libro['imagen']; ?>" style="height: 17rem;" alt="">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title" style="color: #333;"><?php echo $libro['nombre']; ?></h5>
                        <p class="card-text" style="color: #333;"><strong>ID:</strong> <?php echo $libro['id']; ?></p>
                        <p class="card-text" style="color: #333; font-size: 0.9rem;"><?php echo (strlen($libro['descripcion']) > 50) ? substr($libro['descripcion'], 0, 50) . '...' : $libro['descripcion']; ?></p>
                        <div class="mt-auto">
                            <p class="card-text mt-2">
                                <?php if ($libro['precio'] != 0) { ?>
                                    <span style="color: #333; font-weight: bold; font-size: 1.5em;">$<?php echo number_format($libro['precio'], 2); ?></span>
                                <?php } else { ?>
                                    <span style="color: green; font-weight: bold; font-size: 1.5em;">Gratis</span>
                                <?php } ?>
                            </p>
                            <a name="" id="" class="btn btn-primary mt-auto" href="detalle.php?id=<?php echo $libro['id']; ?>&page=<?php echo $page; ?>" role="button" style="background-color: #800000; border-color: #800000;">Ver más</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Paginación -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled"><span class="page-link">Páginas:</span></li>
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                </li>
            <?php } ?>
        </ul>
    </nav>
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
    h5, p {
        font-family: 'Arial', sans-serif;
    }
    h5 {
        font-size: 1.25rem;
        color: #333;
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
</style>
