<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
} else {
    if ($_SESSION['usuario'] == "OK") {
        $nombreUsuario = $_SESSION["nombreUsuario"];
    }
}

include("../config/bd.php");

$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
$txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";
$txtDescripcion = (isset($_POST['txtDescripcion'])) ? $_POST['txtDescripcion'] : "";
$txtPrecio = (isset($_POST['txtPrecio'])) ? $_POST['txtPrecio'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";
$page = (isset($_POST['page'])) ? $_POST['page'] : 1;

switch ($accion) {
    case "Agregar":
        $sentenciaSQL = $conexion->prepare("INSERT INTO libros (nombre, descripcion, precio, imagen) VALUES (:nombre, :descripcion, :precio, :imagen)");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':descripcion', $txtDescripcion);
        $sentenciaSQL->bindParam(':precio', $txtPrecio);
        $nombreArchivo = "";
        if ($txtImagen != "") {
            $fecha = new DateTime();
            $nombreArchivo = $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"];
            $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
            if ($tmpImagen != "") {
                move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
                $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
            }
        } else {
            $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
        }
        $sentenciaSQL->execute();
        header("Location: productos.php?page=$page");
        exit;

    case "Modificar":
        $sentenciaSQL = $conexion->prepare("UPDATE libros SET nombre=:nombre, descripcion=:descripcion, precio=:precio WHERE id=:id");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':descripcion', $txtDescripcion);
        $sentenciaSQL->bindParam(':precio', $txtPrecio);
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

        if ($txtImagen != "") {
            $fecha = new DateTime();
            $nombreArchivo = $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"];
            $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
            if ($tmpImagen != "") {
                move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
                $sentenciaSQL = $conexion->prepare("UPDATE libros SET imagen=:imagen WHERE id=:id");
                $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
                $sentenciaSQL->bindParam(':id', $txtID);
                $sentenciaSQL->execute();
            }
        }
        header("Location: productos.php?page=$page");
        exit;

    case "Cancelar":
        header("Location: productos.php?page=$page");
        exit;

    case "Seleccionar":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtNombre = $libro['nombre'];
        $txtImagen = $libro['imagen'];
        $txtDescripcion = $libro['descripcion'];
        $txtPrecio = $libro['precio'];
        break;

    case "Borrar":
        $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if (isset($libro["imagen"]) && ($libro["imagen"] != "imagen.jpg")) {
            if (file_exists("../../img/" . $libro["imagen"])) {
                unlink("../../img/" . $libro["imagen"]);
            }
        }

        $sentenciaSQL = $conexion->prepare("DELETE FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        header("Location: productos.php?page=$page");
        exit;
}

// Obtener el próximo ID disponible
$nextIDQuery = $conexion->query("SHOW TABLE STATUS LIKE 'libros'");
$nextID = $nextIDQuery->fetch(PDO::FETCH_ASSOC)['Auto_increment'];

// Parámetros de búsqueda y paginación
$letra = isset($_GET['letra']) ? $_GET['letra'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : $page;
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

include("../template/cabecera_admin.php");
?>

<div class="container mt-5">
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
                <button type="submit" class="btn btn-primary btn-block">Buscar</button>
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
        <div class="col-md-5 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Datos del Libro
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="txtID">ID:</label>
                            <input type="text" class="form-control" value="<?php echo ($accion == 'Seleccionar') ? $txtID : $nextID; ?>" name="txtID" id="txtID" placeholder="ID" readonly>
                            <input type="hidden" name="page" value="<?php echo $page; ?>">
                        </div>

                        <div class="form-group">
                            <label for="txtNombre">Nombre:</label>
                            <input type="text" class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre del libro">
                        </div>

                        <div class="form-group">
                            <label for="txtDescripcion">Descripción:</label>
                            <textarea class="form-control" name="txtDescripcion" id="txtDescripcion" rows="3"><?php echo $txtDescripcion; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="txtPrecio">Precio:</label>
                            <input type="number" step="0.01" class="form-control" value="<?php echo $txtPrecio; ?>" name="txtPrecio" id="txtPrecio" placeholder="Precio del libro">
                            <small id="precioWarning" class="form-text text-danger d-none">El precio no puede exceder los $200. La plataforma UniBooks UDG busca apoyar a los estudiantes de la universidad sin ánimo de lucro, promoviendo la pasión por el estudio y la lectura. Por favor, ajuste el precio.</small>
                        </div>

                        <div class="form-group">
                            <label for="txtImagen">Imagen:</label>
                            <?php if ($txtImagen != "") { ?>
                                <img class="img-thumbnail rounded mb-2" src="../../img/<?php echo $txtImagen; ?>" width="100" alt="">
                            <?php } ?>
                            <input type="file" class="form-control-file" name="txtImagen" id="txtImagen">
                        </div>

                        <div class="btn-group" role="group">
                            <button type="submit" name="accion" <?php echo ($accion == "Seleccionar") ? "disabled" : ""; ?> value="Agregar" class="btn btn-success">Agregar</button>
                            <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?> value="Modificar" class="btn btn-warning">Modificar</button>
                            <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?> value="Cancelar" class="btn btn-info">Cancelar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Descripción</th>
                            <th class="text-center">Precio</th>
                            <th class="text-center">Imagen</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaLibros as $libro) { ?>
                            <tr class="libro-row" data-id="<?php echo $libro['id']; ?>" data-page="<?php echo $page; ?>">
                                <td class="text-center align-middle"><?php echo $libro['id']; ?></td>
                                <td class="text-center align-middle"><?php echo $libro['nombre']; ?></td>
                                <td class="text-center align-middle"><?php echo (strlen($libro['descripcion']) > 20) ? substr($libro['descripcion'], 0, 20) . '...' : $libro['descripcion']; ?></td>
                                <td class="text-center align-middle"><?php echo $libro['precio']; ?></td>
                                <td class="text-center align-middle">
                                    <img class="img-thumbnail rounded" src="../../img/<?php echo $libro['imagen']; ?>" width="50" alt="">
                                </td>

                                <td class="text-center align-middle">
                                    <form method="post">
                                        <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id']; ?>" />
                                        <input type="hidden" name="page" value="<?php echo $page; ?>">
                                        <button type="submit" name="accion" value="Seleccionar" class="btn btn-primary btn-sm btn-block">Seleccionar</button>
                                        <button type="submit" name="accion" value="Borrar" class="btn btn-danger btn-sm btn-block mt-2">Borrar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
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

<?php include("../template/pie.php"); ?>

<script>
    // Confirmar la eliminación de un libro
    document.querySelectorAll('button[name="accion"][value="Borrar"]').forEach(function(button) {
        button.addEventListener('click', function(event) {
            if (!confirm('¿Está seguro que desea borrar este libro?')) {
                event.preventDefault();
            }
        });
    });

    // Validar el precio máximo
    document.getElementById('txtPrecio').addEventListener('input', function() {
        var precio = parseFloat(this.value);
        var agregarBtn = document.querySelector('button[name="accion"][value="Agregar"]');
        var modificarBtn = document.querySelector('button[name="accion"][value="Modificar"]');
        var precioWarning = document.getElementById('precioWarning');

        if (precio > 200) {
            precioWarning.classList.remove('d-none');
            agregarBtn.disabled = true;
            modificarBtn.disabled = true;
        } else {
            precioWarning.classList.add('d-none');
            if (document.querySelector('button[name="accion"][value="Agregar"]').disabled) {
                modificarBtn.disabled = false;
            } else {
                agregarBtn.disabled = false;
            }
        }
    });

    // Deshabilitar el botón de agregar cuando se selecciona un libro
    if (<?php echo ($accion == 'Seleccionar') ? 'true' : 'false'; ?>) {
        document.querySelector('button[name="accion"][value="Agregar"]').disabled = true;
    }
</script>

<style>
    .card {
        border: none;
        border-radius: 8px;
    }
    .card-header {
        font-weight: bold;
        font-size: 1.25rem;
    }
    .btn-group .btn {
        margin-right: 5px;
    }
    .btn-primary, .btn-success, .btn-warning, .btn-info {
        transition: background-color 0.2s, border-color 0.2s;
    }
    .btn-primary:hover, .btn-success:hover, .btn-warning:hover, .btn-info:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
    .table-hover tbody tr:hover {
        background-color: #bfbfbf;
        cursor: pointer;
    }
    .table-responsive {
        margin-top: 20px;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .table img {
        transition: transform 0.2s;
    }
    .table img:hover {
        transform: scale(1.1);
    }
</style>
