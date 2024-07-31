<?php
  $codigo = $_GET['id'];
  include 'conexion.php';
  $sql = $conexion->prepare("DELETE FROM FACTURAS WHERE ID_FACTURA = ?;");
  $resultado = $sql->execute([$codigo]);
  $sql = $conexion->prepare("DELETE FROM PRODUCTOSxPEDIDOS WHERE ID_PEDIDO = ?;");
  $resultado = $sql->execute([$codigo]);
  $sql = $conexion->prepare("DELETE FROM PEDIDOS WHERE ID_PEDIDO = ?;");
  $resultado = $sql->execute([$codigo]);

  /** Se alerta de que ha funcionado el delete y se redirige a la pagina de pedidos principal */
  if($resultado === TRUE){
    echo "<script>alert('¡Pedido eliminado correctamente!');location.replace('pedido_home.php');</script>";
  }else{
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Error al eliminar.</strong> Compruebe el producto.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
  }
?>