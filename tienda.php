<?php
// Empezamos la sesión para manejar al usuario logueado
session_start();

// Verificamos el inicio de sesión
if (!isset($_SESSION['usuario'])) {
    // Si no hubiera un inicio de sesión lo mandariamos a login.html.
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda Virtual</title>
</head>
<body>
    <!-- Le damos la bienvenida al usuario que haya iniciado sesión. -->
    <h1>Bienvenido <?php echo htmlspecialchars($_SESSION['usuario']); ?></h1>

    <p>Aquí puedes ver los productos que tenemos disponibles en estos momentos.</p>

    <!-- Creamos un enlace en el que los usuarios puedan cerrar sesión si lo quisieran.  -->
    <p><a href="php/desconectarse.php">Cerrar sesión</a></p>

    <hr>

    <?php
    // Incluimos la conexión a la base de datos
    include 'php/conexion.php';

    // Creamos la conexión
    $bd = new DatabaseConection();
    $conexion = $bd->dbConects();

    // Consulta para obtener los productos
    $sql = "SELECT referencia, nombre, precio FROM productos";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        echo "<h2>Productos disponibles:</h2>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Referencia</th><th>Nombre</th><th>Precio</th><th>Comprar</th></tr>";

        // Mostramos cada producto
        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($fila['referencia']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['precio']) . " €</td>";
            // Botón para poder comprar
            echo "<td>
                    <form action='php/comprar.php' method='POST'>
                        <input type='hidden' name='referencia' value='" . htmlspecialchars($fila['referencia']) . "'>
                        <input type='submit' value='Comprar'>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay productos disponibles.</p>";
    }

    // Cerramos la conexión
    $conexion->close();
    ?>
</body>
</html>
