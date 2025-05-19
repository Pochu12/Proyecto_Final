<?php
include "conexion.php";

$usuario = $_POST["usuario"];

$conexionBD = new DatabaseConection();
$conexion = $conexionBD->dbConects();

//Primero haremos una consulta en donde eliminaremos todas las compras que haya el hecho el usuario X:
$sqlCompras = "DELETE FROM compras WHERE usuario = ?";
$stmtCompras = $conexion->prepare($sqlCompras);
$stmtCompras->bind_param("s", $usuario);
$stmtCompras->execute();
$stmtCompras->close();

// Ahora procedemos a eliminar al usuario:
$sqlUsuario = "DELETE FROM usuarios WHERE usuario = ?";
$stmtUsuario = $conexion->prepare($sqlUsuario);
$stmtUsuario->bind_param("s", $usuario);
$stmtUsuario->execute();

//Si todo coincide saltará un mensaje de exito:
if ($stmtUsuario->affected_rows > 0) {
    $mensaje = "El usuario '$usuario' ha sido eliminado junto con sus compras.";
//si no saltará un error de que no existe el usuario:
} else {
    $mensaje = "No tenemos ningún usuario con el nombre de: '$usuario'.";
}

$stmtUsuario->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuario Eliminado</title>
    <link rel="stylesheet" href="../css/estilazo.css">
</head>
<body>
    <h2><?php echo $mensaje; ?></h2>
    <div class="text-center" style="text-align: center; font-size: 18px;">
        <a href="../login.html" class="btn-volver">Volver al login</a>
    </div>
</body>
</html>
