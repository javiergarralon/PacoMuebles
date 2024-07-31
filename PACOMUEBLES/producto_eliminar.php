<?php
  $codigo = $_GET['id'];
  include 'conexion.php';
  $sql = $conexion->prepare("DELETE FROM PRODUCTOS WHERE ID_PRODUCTO = ?;");
  $resultado = $sql->execute([$codigo]);

  /** Se alerta de que ha funcionado el delete y se redirige a la pagina de productos principal */
  if($resultado === TRUE){
    echo "<script>alert('¡Producto eliminado correctamente!');location.replace('producto_home.php');</script>";
  }else{
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Error al eliminar.</strong> Compruebe el producto.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
  }
?>