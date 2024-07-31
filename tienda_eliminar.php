<?php
  $codigo = $_GET['id'];
  include 'conexion.php';
  $sql = $conexion->prepare("DELETE FROM TIENDAS WHERE ID_TIENDA = ?;");
  $resultado = $sql->execute([$codigo]);
  /** Se alerta de que ha funcionado el delete y se redirige a la pagina de tiendas principal */
  if($resultado === TRUE){
    echo "<script>alert('¡Tienda eliminada correctamente!');location.replace('tienda_home.php');</script>";
  }else{
    echo '<script>alert("¡Error al eliminar! Compruebe la tienda.");location.replace("tiendas.php");</script>';
  }
?>