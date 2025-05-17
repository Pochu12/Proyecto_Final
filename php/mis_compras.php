<?php
//Empezamos la sesión.
session_start();
//Hacemos la comprobación de si el usuario ha iniciado sesión, si no, detendremos la ejecución.
if (!isset($_SESSION['usuario'])) {
    echo "Debes de haber iniciado sesión para poder ver tus compras.";
    exit;
}
//Incluimos los archivo para poder trabajar, primero conexion.phpm para la base de datos.
include 'conexion.php';
//Ahora insertamos la barra de navegación.
include 'navegacion.php';

$usuario = $_SESSION['usuario'];

$bd = new DatabaseConection();
$conexion = $bd->dbConects();

//Ahora hacemos las consultas para poder obtener las compras del usuario:
$consulta = $conexion->prepare("SELECT referencia, fecha FROM compras WHERE usuario = ?");
$consulta->bind_param("s", $usuario);
$consulta->execute();
$consulta->store_result();
$consulta->bind_result($referencia, $fecha);

//Mostraremos un título para que el usuario sepa en donde está.
echo "<h2>Compras de $usuario</h2>";
//Si el usuario tuviera alguna compra nos la mostraria.
if ($consulta->num_rows > 0) {
    echo "<ul>";
    while ($consulta->fetch()) {
        echo "<li>Producto: $referencia - Fecha: $fecha</li>";
    }
    echo "</ul>";
    //pero si no hubiera ninguna compra, les mostrariamos un mensaje avisando de ello:
} else {
    echo "No tienes nada comprado.";
}

$consulta->close();
$conexion->close();
//enlace para poder volver a la tienda.
echo "<br><a href='../tienda.php'>Volver a la tienda</a>";
?>
