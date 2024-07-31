<?php
  $codigo = $_GET['id'];
  include 'conexion.php';
  $sql = $conexion->prepare("DELETE FROM USUARIOS WHERE USER = ?;");
  $resultado = $sql->execute([$codigo]);

  /** Se alerta de que ha funcionado el delete y se redirige a la pagina de tiendas principal */
  if($resultado === TRUE){
    echo "<script>alert('¡Usuario eliminado correctamente!');location.replace('usuarios.php');</script>";
  }else{
    echo '<script>alert("¡Error al eliminar!");location.replace("usuario_buscar.php");</script>';
  }
?>