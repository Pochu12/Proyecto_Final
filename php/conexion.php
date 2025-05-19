<?php
class DatabaseConection {

    //Los datos para poder conectarnos a nuestra base de datos.
    private function dbConect(): bool|mysqli {
        $servidor = "localhost";
        $usuario = "root";
        $contraseña = ""; 
        $dbConect = "practica_final"; 
        $puerto = 3306; 

        // Establecemos la conexión
        $conexion = mysqli_connect(hostname: $servidor, username: $usuario, password: $contraseña, database: $dbConect, port: $puerto);
        mysqli_set_charset(mysql: $conexion, charset: 'utf8');

        // Si falla, nos mostrará el error
        if (!$conexion) {
            die("Error de conexión: " . mysqli_connect_error());
        }

        return $conexion;
    }

    // Hacemos un método público para usar la conexión
    public function dbConects(): bool|mysqli {
        return $this->dbConect();
    }
}
?>
