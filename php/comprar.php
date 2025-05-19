<?php
session_start();

//Hacemos un if para verificar si el usuario ha iniciado sesión, si lo ha hecho podrá comrpar pero si no:
if (!isset($_SESSION['usuario'])) {
    //Le saldrá un error al intentar comprar.
    echo "Tienes que iniciar sesión para comprar.";
    exit;
}

//Incluimos el php de la conexión a la base de datos.
include 'conexion.php';

//Si la solicitud se ha hecho por el método POST, podremos recoger el número de referencia y el nombre del usuario.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $referencia = $_POST['referencia'];
    $usuario = $_SESSION['usuario'];

    //Creamos una instancia nueva a la base de datos:
    $bd = new DatabaseConection();
    $conexion = $bd->dbConects();

    // Hacemos una consulta para poder insertar en la tabla compras , la compra que haya hecho el usuario X, con fecha y nº de referencia:
    $insert = $conexion->prepare("INSERT INTO compras (usuario, referencia, fecha) VALUES (?, ?, NOW())");
    $insert->bind_param("ss", $usuario, $referencia);
    $resultado = $insert->execute();

    //Si todo se ha ejecutado bien, se mostrará un mensaje de compra hecha:
    if ($resultado) {
        echo "La compra ha sido realizada con éxito.";
        //Le proporcionamos un link para que pueda volver a la tienda y seguir comprando.
        echo "<br><a href='../tienda.php'>Volver a la tienda</a>";
        //si no, saltará un error:
    } else {
        echo "Error al realizar la compra.";
    }

    //Cerramos la conexión.
    $insert->close();
    $conexion->close();
}
?>

