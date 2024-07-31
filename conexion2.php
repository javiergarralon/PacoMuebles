<?php
    $conexion2 = new mysqli("localhost", "root", "", "pacomuebles");
    
    if ($conexion2->connect_errno) {
        echo "Fallo al conectar a MySQL: (" . $conexion2->connect_errno . ") " . $conexion2->connect_error;
    }
?>