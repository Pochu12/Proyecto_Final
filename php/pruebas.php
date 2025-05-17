<?php
include 'conexion.php'; //incluimos el pxp conexión para poder relacionarlo.

$bd = new DatabaseConection(); // usamos la clase
$conexion = $bd->dbConects(); // nos intentamos conectar

if ($conexion) {
    echo " Conectado correctamente con practica_final.";
}
?>
