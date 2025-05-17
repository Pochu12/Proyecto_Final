<?php

session_start();
include 'php/conexion.php';
include 'php/navegacion.php';
// Verificar que el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}

// Conectamos con la base de datos
$bd = new DatabaseConection();
$conexion = $bd->dbConects();

// Obtenemos los productos
$consulta = $conexion->prepare("SELECT referencia, nombre, precio FROM productos");
$consulta->execute();
$resultado = $consulta->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda de Móviles</title>
    <link rel="stylesheet" href="css/estilazo.css">
</head>
<body>
    <h2>¿Que te apetece ver hoy?, <?php echo $_SESSION['usuario']; ?></h2>
    <h3>Catálogo de productos</h3>

    <?php while ($producto = $resultado->fetch_assoc()) { ?>
        <div style="border:1px solid #ccc; padding:10px; margin:10px;">
            <p><strong>ID:</strong> <?php echo $producto['referencia']; ?></p>
            <p><strong>Nombre:</strong> <?php echo $producto['nombre']; ?></p>
            <p><strong>Precio:</strong> <?php echo $producto['precio']; ?> €</p>

            <form action="php/comprar.php" method="POST">
                <input type="hidden" name="referencia" value="<?php echo $producto['referencia']; ?>">
                <input type="submit" value="Comprar">
            </form>
        </div>
    <?php } ?>
</body>
</html>
