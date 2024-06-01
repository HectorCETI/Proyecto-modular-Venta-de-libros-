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
        header("Location:productos.php?page=$page");
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
        header("Location:productos.php?page=$page");
        exit;

    case "Cancelar":
        header("Location:productos.php?page=$page");
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
        header("Location:productos.php?page=$page");
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

<div class="container">
    <!-- Formulario de Búsqueda -->
    <form method="GET" class="mb-4" style="margin-top: 20px;">
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
                <button type="submit" class="btn btn-primary">Buscar</button>
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
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
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
                        </div>

                        <div class="form-group">
                            <label for="txtImagen">Imagen:</label>
                            <?php if ($txtImagen != "") { ?>
                                <img class="img-thumbnail rounded" src="../../img/<?php echo $txtImagen; ?>" width="50" alt="">
                            <?php } ?>
                            <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Imagen del libro">
                        </div>

                        <div class="btn-group" role="group" aria-label="">
                            <button type="submit" name="accion" <?php echo ($accion == "Seleccionar") ? "disabled" : ""; ?> value="Agregar" class="btn btn-success">Agregar</button>
                            <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?> value="Modificar" class="btn btn-warning">Modificar</button>
                            <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?> value="Cancelar" class="btn btn-info">Cancelar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <table class="table table-bordered bg-white">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaLibros as $libro) { ?>
                        <tr>
                            <td><?php echo $libro['id']; ?></td>
                            <td><?php echo $libro['nombre']; ?></td>
                            <td><?php echo $libro['descripcion']; ?></td>
                            <td><?php echo $libro['precio']; ?></td>
                            <td>
                                <img class="img-thumbnail rounded" src="../../img/<?php echo $libro['imagen']; ?>" width="50" alt="">
                            </td>

                            <td>
                                <form method="post">
                                    <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id']; ?>" />
                                    <input type="hidden" name="page" value="<?php echo $page; ?>">
                                    <button type="submit" name="accion" value="Seleccionar" class="btn btn-primary btn-sm btn-block">Seleccionar</button>
                                    <button type="button" class="btn btn-danger btn-sm btn-block mt-2" onclick="confirmarBorrar(<?php echo $libro['id']; ?>, <?php echo $page; ?>)">Borrar</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
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
    function confirmarBorrar(id, page) {
        if (confirm('¿Está seguro de que desea borrar este libro? Esta acción no se puede deshacer. El ID se perderá y el libro no podrá recuperarse a menos que se dé de alta como un nuevo libro con un ID diferente.')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'productos.php';
            
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'txtID';
            input.value = id;
            
            var accion = document.createElement('input');
            accion.type = 'hidden';
            accion.name = 'accion';
            accion.value = 'Borrar';
            
            var pageInput = document.createElement('input');
            pageInput.type = 'hidden';
            pageInput.name = 'page';
            pageInput.value = page;
            
            form.appendChild(input);
            form.appendChild(accion);
            form.appendChild(pageInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
