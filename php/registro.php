<?php
// Nos conectamos a la base de datos:
include 'conexion.php';
// Verificamos si se envió el formulario por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recogemos los datos del formulario con POST
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $genero = $_POST['genero'];

    // Comprobamos que no estén vacíos los campos
    if (empty($usuario) || empty($contraseña) || empty($nombre) || empty($apellidos) || empty($correo) || empty($fecha_nacimiento) || empty($genero)) {
        echo "Rellena todos los campos.";
        exit;
    }

    // Encriptamos la contraseña con password_hash
    $contraseña_segura = password_hash($contraseña, PASSWORD_DEFAULT);

    // Nos conectamos a la base de datos
    $bd = new DatabaseConection();
    $conexion = $bd->dbConects();

    // Insertamos en la tabla de usuarios
    $insert_usuario = $conexion->prepare("INSERT INTO usuarios (usuario, contraseña) VALUES (?, ?)");
    $insert_usuario->bind_param("ss", $usuario, $contraseña_segura);
    $resultado_usuario = $insert_usuario->execute();

    if ($resultado_usuario) {
        // Ahora en clientes:
        $insert_cliente = $conexion->prepare("INSERT INTO clientes (nombre, apellidos, correo, fecha_nacimiento, genero) VALUES (?, ?, ?, ?, ?)");
        $insert_cliente->bind_param("sssss", $nombre, $apellidos, $correo, $fecha_nacimiento, $genero);
        $resultado_cliente = $insert_cliente->execute();

        if ($resultado_cliente) {
            // Nos redirigimos al login después de registrarnos
            header("Location: ../login.html"); 
            exit(); // Paramos el script 
        } else {
            echo "No se ha podido guardar.";
        }

        $insert_cliente->close();
    } else {
        echo "Ese usuario ya existe.";
    }

    // Cerramos todo
    $insert_usuario->close();
    $conexion->close();
}
?>

