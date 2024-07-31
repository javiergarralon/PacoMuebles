<?php
  $codigo = $_GET['id'];
  include 'conexion.php';
  $sql = $conexion->prepare("DELETE FROM PROVEEDORES WHERE ID_PROVEEDOR = ?;");
  $resultado = $sql->execute([$codigo]);

  /** Se alerta de que ha funcionado el delete y se redirige a la pagina de proveedores principal */
  if($resultado === TRUE){
    echo "<script>alert('¡Proveedor eliminado correctamente!');location.replace('proveedor_home.php');</script>";
  }else{
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Error al eliminar.</strong> Compruebe el producto.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
  }
?>