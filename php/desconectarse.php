<?php
//Inicializamos la sesión
session_start(); 
//Destruimos toda la sesión
session_destroy();
//Redirigimos al usuario a login.html para que pueda iniciar sesión o registrarse.
header("Location: ../login.html");
exit();
?>

