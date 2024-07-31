<?php
    define("DSN", "mysql:host=localhost;dbname=pacomuebles");
    define("USERNAME", "root");
    define("PASSWORD","");
    $options = array(PDO::ATTR_PERSISTENT => true);

    try{
        $conexion = new PDO(DSN, USERNAME, PASSWORD, $options);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $ex){
        echo "<p class='text-center font-weight-bold mt-2' style='color:#c0392b'>Fallo conexión.</p>".$ex->getMessage();
    }   
?>