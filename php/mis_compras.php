<?php
session_start();

//Hacemos un if para verificar si el usuario ha iniciado sesión, si lo ha hecho podrá verlo sino:
if (!isset($_SESSION['usuario'])) {
    //saldrá un error de que debe iniciar sesión.
    echo "Debes de iniciar sesión para poder ver tus compras.";
    exit;
}
//Incluimos la conexión a la base de datos y a la barra de navegación.
include 'conexion.php';
include 'navegacion.php';

//Obtenemos el usuario que esté actualmente.
$usuario = $_SESSION['usuario'];
$bd = new DatabaseConection();
$conexion = $bd->dbConects();

//Hacemos una consulta en la que obtenemos las compras del usuarios, uniendo tabla compras con productos para
//así poder sacar el precio.
$consulta = $conexion->prepare("
    SELECT c.referencia, c.fecha, p.precio 
    FROM compras c 
    JOIN productos p ON c.referencia = p.referencia 
    WHERE c.usuario = ?
");


$consulta->bind_param("s", $usuario);
$consulta->execute();
//Obtenemos el resultado de la consulta.
$resultado = $consulta->get_result();
?>

<!-- Hacemos un HTML para poder mostrar la tabla en donde veremos las compras del usuario: -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tus Compras</title>
    <link rel="stylesheet" href="../css/estilazo.css"> 
    <style>

    body {
        background-color: #fef5f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding-top: 60px; /* margen superior por la barra de navegación fija */
    }

    h2 {
        text-align: center;
        color: #8b0000;
        margin-top: 20px;
        font-size: 2em;
    }

    table {
        border-collapse: collapse;
        width: 90%;
        max-width: 900px;
        margin: 40px auto 20px auto;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    th {
        background-color: #b22222;
        color: #ffffff;
        padding: 16px;
        font-size: 1.1em;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 14px;
        text-align: center;
        border-bottom: 1px solid #f1c4c4;
        font-size: 1em;
        color: #333;
        background-color: #ffffff;
    }

    tr:hover td {
        background-color: #f9d6d6;
        transition: background-color 0.2s ease-in-out;
    }

    .total {
        max-width: 900px;
        margin: 20px auto;
        font-size: 1.3rem;
        font-weight: bold;
        color: #8b0000;
        text-align: right;
        padding-right: 20px;
    }

    a {
        display: block;
        text-align: center;
        margin-top: 30px;
        font-weight: bold;
        color: #8b0000;
        text-decoration: none;
        font-size: 1.1em;
    }

    a:hover {
        text-decoration: underline;
    }
</style>



</head>
<body>

<?php
//Título principal:
echo "<h2>Tus compras</h2>";

//Calculamos el total gastado:
$total = 0;

//Sí hubiera compras, se muestran en la tabla:
if ($resultado->num_rows > 0) {
    echo "<table>";
    echo "<thead><tr><th>Producto</th><th>Fecha y hora</th><th>Precio (€)</th></tr></thead><tbody>";
    //mostramos las compras en una fila:
    while ($fila = $resultado->fetch_assoc()) {
        $referencia = htmlspecialchars($fila['referencia']);
        $fecha = htmlspecialchars($fila['fecha']);
        $precio = number_format($fila['precio'], 2, ',', '.');
        //Suma total:
        $total += $fila['precio'];

        echo "<tr>
                <td>$referencia</td>
                <td>$fecha</td>
                <td>$precio</td>
              </tr>";
    }
    echo "</tbody></table>";


    $total_formateado = number_format($total, 2, ',', '.');
    echo "<div class='total'>Total: $total_formateado €</div>";

    //si no tuvieramos compras, mostrariamos el mensaje:
} else {
    echo "<p style='text-align:center;'>No has realizado ninguna compra todavía.</p>";
}

//Cerramos la consulta y conexión.
$consulta->close();
$conexion->close();

//Le damos un enlace al usuario para que pueda volver a la tienda:
echo "<a href='../tienda.php'>Volver a la tienda</a>";
?>

</body>
</html>



