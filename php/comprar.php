<?php
include 'php/navegacion.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    echo "Tienes que iniciar sesión para comprar.";
    exit;
}

include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $referencia = $_POST['referencia'];
    $usuario = $_SESSION['usuario'];

    $bd = new DatabaseConection();
    $conexion = $bd->dbConects();

    // Insertamos los datos tal cual están definidos en la tabla 'compras'
    $insert = $conexion->prepare("INSERT INTO compras (usuario, referencia, fecha) VALUES (?, ?, NOW())");
    $insert->bind_param("ss", $usuario, $referencia);
    $resultado = $insert->execute();

    if ($resultado) {
        echo "Compra realizada con éxito.";
        echo "<br><a href='../tienda.php'>Volver a la tienda</a>";
    } else {
        echo "Error al realizar la compra.";
    }

    $insert->close();
    $conexion->close();
}
?>

