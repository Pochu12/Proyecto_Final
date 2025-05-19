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

// Obtenemos los productos (ahora también obtenemos la imagen)
$consulta = $conexion->prepare("SELECT referencia, nombre, precio, imagen, descripcion FROM productos");
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
    <h2>¿Que te apetece ver hoy? <?php echo $_SESSION['usuario']; ?></h2>
    
    <div style="text-align:center;">
        <h3>Productos en oferta:</h3>
    </div>

    <?php while ($producto = $resultado->fetch_assoc()) { ?>
        <div style="border:1px solid #ccc; padding:10px; margin:0; font-size: 18px; text-align: center;">
            <!-- Mostramos la imagen de los productos-->
            <img src="img/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" style="max-width:200px; display: block; margin: 0 auto 10px;">
            
            <p><strong>ID:</strong> <?php echo $producto['referencia']; ?></p>
            <p><strong>Nombre:</strong> <?php echo $producto['nombre']; ?></p>
            <p><strong>Precio:</strong> <?php echo $producto['precio']; ?> €</p>
            <p><strong>Detalles:</strong> <?php echo $producto['descripcion']; ?></p>

            <form action="php/comprar.php" method="POST">
                <input type="hidden" name="referencia" value="<?php echo $producto['referencia']; ?>">
                <input type="submit" class="boton-comprar" value="Comprar">
            </form>
        </div>
    <?php } ?>
</body>
</html>

