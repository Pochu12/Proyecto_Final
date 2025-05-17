<?php
// Incluimos la conexion.php
include 'conexion.php';

// Verificamos si se ha mandado por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Guardamos lo que escribió el usuario
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Verificamos que no estén vacíos
    if (empty($usuario) || empty($contraseña)) {
        echo "Por favor, no dejes ningún campo vacío.";
        exit; // Si falta algo, salimos del programa
    }

    // Aquí creamos la conexión con la base de datos
    $bd = new DatabaseConection();
    $conexion = $bd->dbConects();

    // Preparamos la consulta para evitar problemas (inyección SQL)
    $consulta = $conexion->prepare("SELECT contraseña FROM usuarios WHERE usuario = ?");
    
    $consulta->bind_param("s", $usuario);
    
    // Ejecutamos la consulta
    $consulta->execute();
    
    // Guardamos el resultado para ver si hay filas
    $consulta->store_result();

    // Si encontramos un usuario con ese nombre
    if ($consulta->num_rows == 1) {
        // Guardamos la contraseña que está en la base de datos.
        $consulta->bind_result($hash_guardado);
        $consulta->fetch();

        // Comparamos la contraseña que escribió el usuario con la que está guardada
        if (password_verify($contraseña, $hash_guardado)) {
            session_start();
            $_SESSION['usuario'] = $usuario; // Guardamos el nombre en la sesión
            header("Location: ../tienda.php"); // Lo mandamos a la tienda
            exit;
        } else {
            echo "La contraseña está mal.";
        }
    } else {
        echo "No existe un usuario con ese nombre.";
    }

    // Cerramos la consulta y conexion
    $consulta->close();
    $conexion->close();
}
?>
