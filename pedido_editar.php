<?php
    session_start();
    if(!isset($_SESSION['USER'])){
        header('Location: index.php');
    }
?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
  <link rel="shortcut icon" href="/img/logo_up.png" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/inicio.css" rel="stylesheet">
  <script type='text/javascript' src='js/hora.js'></script>
  <title>Pedidos</title>

</head>

<body onload="hora_act()">

  <div class="d-flex" id="wrapper">
    <!--Sidebar-->
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading"><a class="text-dark" href="inicio.php"><img src="img/home_pacologo.png" style="width:200px"></a></div>
      <div class="list-group list-group-flush">
        <a href="tienda_home.php" class="list-group-item list-group-item-action bg-light color_bold_font">Gestión de tiendas</a>
        <a href="proveedor_home.php" class="list-group-item list-group-item-action bg-light color_bold_font">Gestión de proveedores</a>
        <a href="producto_home.php" class="list-group-item list-group-item-action bg-light color_bold_font">Gestión de productos</a>
        <a href="pedido_home.php" class="list-group-item list-group-item-action bg-light color_bold_font">Gestión de pedidos</a>
        <a href="facturas.php" class="list-group-item list-group-item-action bg-light color_bold_font">Gestión de facturas</a>        
        <a href="informacion.php" class="list-group-item list-group-item-action bg-light color_bold_font">Información</a>
      </div>
    </div>
    <!--/#sidebar-wrapper-->

    <!-- navbar -->
    <div id="page-content-wrapper">
      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
      <button class="btn color_use text-warning" id="menu-toggle"><i class="fas fa-bars"></i></button>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-chevron-down"></i>
        </button>
        <!--fecha y hora-->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">    
            <div class="d-flex float-left mr-3">             
              <img class="mx-auto d-block img-fluid" src="img/logo_transparente.png" style="width: 50px"/>
            </div>        
            <div id="fecha">               
              <!--script fecha-->        
              <script type='text/javascript' src='js/fecha.js'></script>
              <!--/script fecha-->     
              <div id="reloj">
                <form name="form_reloj">
                  <input type="text" name="reloj" id="casillareloj" disabled="disabled">
                </form>
              </div>
            </div>
        <!--/fecha y hora-->
        <!--usuario-->
            <li class="nav-item active dropdown" id="centrarUsuario">
              <a class="nav-link dropdown-toggle color_bold_font" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 <?php echo $_SESSION['USER'];?>
                 <img src="img/user.png">
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item color_bold_font" data-toggle="modal" data-target="#user_config">Cambiar datos</a>
                <?php
                  if($_SESSION['PERMISSION'] == 1){
                    echo '<a class="dropdown-item color_bold_font" href="usuarios.php">Gestión Usuarios</a>';
                  }
                ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item color_bold_font" data-toggle="modal" data-target="#close_session">Cerrar sesión</a>                
              </div>
            </li>
          </ul>
        </div>
      </nav>


      <!--PHP cambio user/password-->
      <?php
        if(isset($_POST['btn_change'])){
          include 'conexion.php';
          
          $nueva_pass = $_POST['newPassword'];
          $nuevo_usuario = strtolower($_POST['newUser']);
          $pass_actual = $_POST['password'];
          $user_actual = $_POST['user'];
          
          $sentencia = $conexion->prepare('SELECT * FROM USUARIOS WHERE USER = ?');
          $sentencia->execute([$nuevo_usuario]);
          $resultado = $sentencia->fetch(PDO::FETCH_OBJ);

          //si sólo es el usuario
          if($nueva_pass == "" && $nuevo_usuario != ""){
            //si el usuario es diferente al actual
            if($nuevo_usuario != $user_actual){
              if($resultado === FALSE){
                $sentencia = $conexion->prepare('SELECT * FROM USUARIOS WHERE USER = ?');
                $sentencia->execute([$user_actual]);
                $resultado = $sentencia->fetch(PDO::FETCH_OBJ);
                if($resultado->PASSWORD === $pass_actual){
                  $sentencia = $conexion->prepare('UPDATE USUARIOS SET USER = ? WHERE USER = ?');
                  $sentencia->execute([$nuevo_usuario, $user_actual]);
                  session_destroy();
                  echo "<script>alert('¡Usuario modificado! SE CIERRA LA SESIÓN.');location.replace('index.php');</script>";
                } 
              //si el usuario ya está cogido/existe  
              }else{
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>¡Ese usuario ya existe!</strong> Pruebe con otro.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
              }
            //si el usuario es el actual  
            }else{
              echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                      <strong>¡El usuario es el actual!</strong> Pruebe con otro.
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
            }
          //si sólo se cambiaa la password  
          }else if($nueva_pass != "" && $nuevo_usuario ==""){ 
            $sentencia = $conexion->prepare('SELECT * FROM USUARIOS WHERE USER = ?');
            $sentencia->execute([$user_actual]);
            $resultado = $sentencia->fetch(PDO::FETCH_OBJ); 
            //si la contraseña actual es la misma           
            if($resultado->PASSWORD == $pass_actual){
              //si la nueva contraseña es la actual
              if($nueva_pass === $resultado->PASSWORD){
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>¡La contraseña es la actual!</strong> ¿Realmente quiere cambiarla?
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
              //si la nueva contraseña es realmente nueva
              }else{
                $sentencia = $conexion->prepare('UPDATE USUARIOS SET PASSWORD = ? WHERE USER = ?');
                $sentencia->execute([$nueva_pass, $user_actual]);
                session_destroy();
                echo "<script>alert('¡Contraseña modificada! SE CIERRA LA SESIÓN.');location.replace('index.php');</script>";                  
              } 
              //si no se acierta la contraseña actual
            }else{
              echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                      <strong>¡Contraseña actual incorrecta!</strong> Revisa el campo.
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
            }
          //si se cambian usuario y contraseña
          }else if($nueva_pass != "" && $nuevo_usuario != ""){
            $sentencia = $conexion->prepare('SELECT * FROM USUARIOS WHERE USER = ?');
            $sentencia->execute([$user_actual]);
            $resultado = $sentencia->fetch(PDO::FETCH_OBJ);
            //Si la pass actual coincide
            if($resultado->PASSWORD === $pass_actual){
              //si los nuevos usuario/contraseña coinciden con los actuales
              if($nuevo_usuario == $user_actual && $resultado->PASSWORD === $nueva_pass){
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>¡El usuario y contraseña son los actuales!</strong> ¿Realmente desea cambiarlos?
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
              //si la nueva contraseña coincide con la actual
              }elseif($nuevo_usuario != $user_actual && $resultado->PASSWORD === $nueva_pass){
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>¡La contraseña es la actual!</strong> ¿Realmente desea cambiarla?
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
                //si el usuario coincide con el actual
              }elseif($nuevo_usuario == $user_actual && $resultado->PASSWORD != $nueva_pass){
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>¡El usuario es el actual!</strong> ¿Realmente desea cambiarlo?
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
              //si ninguno coincide y está libre
              }else{
                $sentencia = $conexion->prepare('SELECT * FROM USUARIOS WHERE USER = ?');
                $sentencia->execute([$nuevo_usuario]);
                $resultado = $sentencia->fetch(PDO::FETCH_OBJ);
                if($resultado === FALSE){
                  $sentencia = $conexion->prepare('UPDATE USUARIOS SET USER = ?, PASSWORD = ? WHERE USER = ?');
                  $sentencia->execute([$nuevo_usuario,$nueva_pass,$user_actual]);
                  session_destroy();
                  echo "<script>alert('¡Usuario y contraseña modificados! SE CIERRA LA SESIÓN.');location.replace('index.php');</script>";
                }else{
                  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                          <strong>¡El usuario ya existe!</strong> Prueba con otro.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>'; 
                } 
              }
            }else{
              echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                      <strong>¡La contraseña actual no coincide!</strong> Compruebe los campos.
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>'; 
            }               
          }else if($nuevo_usuario == "" && $nueva_pass == ""){
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>¡Introduzca los campos nuevos!</strong> ¿Realmente quieres cambiarlos?
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
          }
        }
      ?>
      <!--/#PHP cambio user/password -->
     <!--modal configuración usuario-->
     <div class="modal fade" id="user_config" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="user_configLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="user_configLabel">Cambiar usuario/contraseña de <?php echo $_SESSION['USER'];?>.</h5>
            </div>
            <div class="modal-body">
              <form class="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                <div class="form-group ">
                  <label for="username">Nuevo usuario:</label><br>
                  <input type="text" name="newUser" class="form-control" autocomplete="off" placeholder="Usuario">
                </div>
                <div class="form-group ">
                  <label for="password">Nueva contraseña:</label><br>
                  <input type="password" name="newPassword" class="form-control" placeholder="Contraseña">
                </div>
                <hr>
                <div class="form-group ">
                  <label for="password">Contraseña actual:</label><br>
                  <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>
                <div class="form-group d-flex justify-content-center">
                  <input type="hidden" name="user" value="<?php echo $_SESSION['USER'];?>">
                  <button   class="btn btn-lg btn-block color_use text-warning" type="submit" name="btn_change"><i class="fas fa-exchange-alt"></i></button>
                </div>
              </form>
            </div>
            <div class="modal-footer">              
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
          </div>
        </div>
      </div>
      <!--/modal-->

       <!--modal cerrar sesion-->
       <div class="modal fade" id="close_session" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="close_sessionLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="close_sessionLabel">¿Cerrar sesión?</h5>
            </div>
            <div class="modal-footer">              
              <a type="button" class="btn color_use text-warning" href="cerrar_sesion.php"><i class="fas fa-check"></i></a>
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
          </div>
        </div>
      </div>
      <!--/modal-->


    <!--PHP PARA CARGAR LOS DATOS TRAIDOS-->
    <?php
      include_once 'conexion.php';
      $id = $_GET['id'];
      $sql = $conexion->prepare("SELECT * FROM PEDIDOS JOIN PRODUCTOSxPEDIDOS ON PEDIDOS.ID_PEDIDO = PRODUCTOSxPEDIDOS.ID_PEDIDO WHERE PEDIDOS.ID_PEDIDO = ?;");
      $sql ->execute([$id]);
      $pedido = $sql->fetch(PDO::FETCH_OBJ);
    ?>
    <!--/#PHP-->
    <!--PHP EDITAR PEDIDOS-->
     
    <?php
      if(isset($_POST['btn_editar_pedido'])){
        $conexion->exec("SET CHARACTER SET utf8");

        $id_pedido = $_POST['id_pedido'];
        $fecha_pedido = $_POST['fecha_pedido2'];
        $observaciones = $_POST['observaciones2'];
        $estadoactual = $_POST['estadoactual'];
        $estado2 = $_POST['estado2'];
        $optionestado2 = intval($estado2);

        if($estadoactual == 0 && $estado2 ==1){
          $sql = $conexion->prepare("UPDATE PEDIDOS SET ESTADO = ?, FECHA_ENTREGA = ?, FECHA_PEDIDO = ?, OBSERVACIONES = ? WHERE ID_PEDIDO = ?;");
          $resultado = $sql->execute([$optionestado2, date('Y-m-d'),$fecha_pedido,$observaciones,$id_pedido]);
          /** Se necesita refrescar la página manualmente ya que sino no se ve la lista actualizada */
           
          if($resultado === TRUE){
            echo "<script>alert('¡Pedido editado correctamente!')</script>";
            echo  "<script type='text/javascript' src='js/refrescar.js'></script>";                  
          }else{
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Error al editar.</strong> Compruebe los campos.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
          }        
        }else if($estadoactual == 1 && $estado2 == 0){
          $sql = $conexion->prepare("UPDATE PEDIDOS SET ESTADO = ?, FECHA_ENTREGA = ?, FECHA_PEDIDO = ?, OBSERVACIONES = ? WHERE ID_PEDIDO = ?;");
          $resultado = $sql->execute([$optionestado2, NULL,$fecha_pedido,$observaciones,$id_pedido]);
          if($resultado === TRUE){
            echo "<script>alert('¡Pedido editado correctamente!')</script>";
            echo  "<script type='text/javascript' src='js/refrescar.js'></script>";
          }else{
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Error al editar.</strong> Compruebe los campos.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
          }   
        }else if($estadoactual == 1 && $estado2 == 1){
          $sql = $conexion->prepare("UPDATE PEDIDOS SET FECHA_PEDIDO = ?, OBSERVACIONES = ? WHERE ID_PEDIDO = ?;");
          $resultado = $sql->execute([$fecha_pedido,$observaciones,$id_pedido]);
          if($resultado === TRUE){
            echo "<script>alert('¡Pedido editado correctamente!')</script>";
            echo  "<script type='text/javascript' src='js/refrescar.js'></script>";
          }else{
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Error al editar.</strong> Compruebe los campos.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
          } 
        }else if($estadoactual == 0 && $estado2 == 0){
          $sql = $conexion->prepare("UPDATE PEDIDOS SET FECHA_PEDIDO = ?, OBSERVACIONES = ? WHERE ID_PEDIDO = ?;");
          $resultado = $sql->execute([$fecha_pedido,$observaciones,$id_pedido]);
          if($resultado === TRUE){
            echo "<script>alert('¡Pedido editado correctamente!')</script>";
            echo  "<script type='text/javascript' src='js/refrescar.js'></script>";
          }else{
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Error al editar.</strong> Compruebe los campos.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
          } 
        }  
      }  
    ?>
    <!--/#PHP EDITAR PEDIDO-->
    <!--modal eliminar PEDIDO-->
    <div class="modal fade" id="eliminarPedido" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="eliminarPedidoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="eliminarPedidoLabel">¿Desea eliminar?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Esta acción no se puede deshacer.</p>
          </div>
          <div class="modal-footer">              
            <a href="pedido_eliminar.php?id=<?php echo $pedido->ID_PEDIDO;?>" type="button" class='btn btn-outline-danger bg-danger text-white'><i class='fas fa-trash-alt'></i></a>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i></button>
          </div>
        </div>
      </div>
    </div>
    <!--/modal eliminar PEDIDO-->  
    <!--modal lista de PEDIDOS-->
    <div class="modal" id="listaPedidos" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="listaPedidosLabel" aria-hidden="true">
      <div class="text-center" style="width:80%;margin: 30px auto;">
        <div class="modal-content">
          <div class="modal-header">
            <img id="img_modal" src="img/pedidos.png">
            <div class="container d-flex justify-content-center mb-3">
              <form class="form" action="pedido_buscar.php" method="post">
                <div class="container-fluid row">
                  <div class="col-6 p-0">
                    <select class="custom-select mb-1 color_font" name="searchFilter">
                      <option value="ID_PEDIDO">ID</option>
                      <option value="NOMBRE_PROVEEDOR">Proveedor</option>
                      <option value="NOMBRE_TIENDA">Tienda</option>
                      <option value="FECHA_PEDIDO">F. Pedido</option>
                      <option value="FECHA_ENTREGA">F. Entrega</option>
                    </select>
                  </div>
                  <div class="col-6 p-0">
                    <select class="custom-select mb-1 color_font" name="searchActivity">
                      <option value="" selected>--</option>
                      <option value="1">ENTREGADO</option>
                      <option value="0">EN CAMINO</option>
                    </select>
                  </div>
                </div>
                <div class="container-fluid row">
                  <div class="col-9 p-0">
                    <input type="searchInput" class="form-control color_font" name="inputSearch" autocomplete="off">
                  </div>
                  <div class="col-3 ">
                    <button name="searchBtn" title="Filtrar" class="btn color_text bg-warning" title="Buscar"><i class="fas fa-search"></i></button>    
                  </div>
                </div>          
              </form>
            </div>  
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">  
          <?php
            include 'conexion2.php';
            $consulta = "SELECT * FROM PEDIDOS ORDER BY FECHA_PEDIDO DESC";
            $res = mysqli_query($conexion2, $consulta);
            echo '<div class="container">';
              echo '<table class="table table-striped table-hover color_font" id="tabla_modal">';
                echo '<thead>';
                  echo "<tr>";
                    echo "<th>ID Pedido</th>";
                    echo "<th>Proveedor</th>";
                    echo "<th>Tienda</th>";
                    echo "<th>F. Pedido</th>";
                    echo "<th>F. Entrega</th>";
                    echo "<th>Estado</th>";
                    echo "<th></th>";
                    if($_SESSION['PERMISSION'] == 1){
                      echo "<th></th>";
                    } 
                  echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while($linea = mysqli_fetch_array($res, MYSQLI_ASSOC)){
                  echo "<tr>";
                    echo "<td>".$linea['ID_PEDIDO']."</td>";
                    echo "<td>".$linea['NOMBRE_PROVEEDOR']."</td>";
                    echo "<td>".$linea['NOMBRE_TIENDA']."</td>";
                    echo "<td>".date("d/m/Y",strtotime($linea['FECHA_PEDIDO']))."</td>";
                    $fecha_entrega = date("d/m/Y",strtotime($linea['FECHA_ENTREGA']));
                    if($fecha_entrega == "01/01/1970")
                    {
                      $fecha_entrega = "";
                    }
                    echo "<td>".$fecha_entrega."</td>";
                    if($linea['ESTADO']==0){
                      echo "<td>EN CAMINO</td>";
                    }else{
                      echo "<td>ENTREGADO</td>";
                    }
                    echo "<td><a href='pedido_editar.php?id=".$linea['ID_PEDIDO']."'><button title='Editar' class='btn color_use text-warning'><i class='fas fa-edit'></i></button></a></td>";
                    if($_SESSION['PERMISSION'] == 1 && $linea['ESTADO']==0){
                      echo "<td><a href='pedido_eliminar.php?id=".$linea['ID_PEDIDO']."'><button title='Eliminar' class='btn btn-outline-danger bg-danger text-white'><i class='fas fa-trash-alt'></i></button></a></td>";
                    }else if($_SESSION['PERMISSION'] == 1){
                      echo "<td></td>";
                    } 
                  echo "</tr>";
                }
                echo "</tbody>";
              echo "</table>";
            echo "</div>";
          ?>
          </div>
          <div class="modal-footer">              
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i></button>
          </div>
        </div>
      </div>
    </div>
    <!--/modal-->  
    <!--contenido pagina-->
    
    <div class="container my-5 color_font"> 
      <div class="d-flex justify-content-center">
        <img src="img/pedidos.png">
      </div>
      <h3 class="text-center my-5">Pedido <?php echo $pedido->ID_PEDIDO?>:</h3>
        <form method="POST" action="" role="form">                  
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>ID Pedido:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text color_font"><i class="fas fa-key"></i></span>
                  </div>
                  <input type="text" class="form-control color_font" autocomplete="off" value="<?php echo $pedido->ID_PEDIDO?>" disabled required>
                </div>
              </div>
              <div class="form-group col-md-4">                
                <div class="form-group">
                  <label for="tiendas">Proveedor:</label>  
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text color_font"><i class="fas fa-user-plus"></i></span>
                    </div>          
                    <input type="text" class="form-control color_font" autocomplete="off" value="<?php echo $pedido->ID_PROVEEDOR." - ".$pedido->NOMBRE_PROVEEDOR?>" disabled required>
                  </div>
                </div>
              </div>  
              <div class="form-group col-md-4">                
                <div class="form-group">
                  <label for="tiendas">Tienda:</label> 
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text color_font"><i class="fa fa-shopping-bag"></i></span>
                    </div>          
                    <input type="text" class="form-control color_font" autocomplete="off" value="<?php echo $pedido->ID_TIENDA." - ".$pedido->NOMBRE_TIENDA?>" disabled required>
                  </div>
                </div>  
              </div> 
            </div>                       
            <div class="form-row">                
              <div class="form-group col-md-6">
                <label for="fecha_pedido2">Fecha pedido:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text color_font"><i class="fas fa-calendar-day"></i></span>
                  </div>
                  <input type="date" class="form-control color_font" name="fecha_pedido2" value="<?php echo $pedido->FECHA_PEDIDO?>" autocomplete="off" required>
                </div>
              </div>    
              <div class="form-group col-md-6">
                <label for="estado2">Estado:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text color_font"><i class="fas fa-power-off"></i></span>
                  </div>
                  <select class="custom-select color_font" name="estado2">
                    <option value='0'>EN CAMINO</option>
                    <option  <?php if($pedido->ESTADO == 1){ echo 'selected'; } ?> value='1'>ENTREGADO</option>
                  </select>
                </div>
              </div>              
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="observaciones2">Observaciones:</label>
                <textarea class="form-control color_font" name="observaciones2" rows="2" maxlength="200"><?php echo $pedido->OBSERVACIONES ?></textarea>
              </div>
            </div>
            <hr>
            <div class="container-fluid my-4">
            <?php
              $id = $_GET['id'];
              include_once 'conexion2.php';
              $consulta = "SELECT * FROM PRODUCTOSxPEDIDOS WHERE ID_PEDIDO = '$id'";
              $res = mysqli_query($conexion2, $consulta);
              echo '<h3 class="text-center my-1">Productos</h3>';
              echo '<table class="table table-striped table-hover text-center color_font" id="tabla_modal">';
                echo '<thead>';
                  echo "<tr>";
                    echo "<th>ID</th>";
                    echo "<th>Producto</th>";
                    echo "<th>Precio unidad</th>";
                    echo "<th>Cantidad</th>";
                    echo "<th>IVA</th>";
                    echo "<th>Total</th>";
                  echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while($linea = mysqli_fetch_array($res, MYSQLI_ASSOC)){
                  echo "<tr>";
                    echo "<td>".$linea['ID_PRODUCTO']."</td>";
                    echo "<td>".$linea['NOMBRE_PRODUCTO']."</td>";
                    echo "<td>".$linea['PRECIO_PRODUCTO']."€</td>";
                    echo "<td>".$linea['CANTIDAD']."</td>";
                    echo "<td>".$linea['IVA']."%</td>";
                    echo "<td>".$linea['PRECIO']."€</td>";
                  echo "</tr>";
                }
                echo "</tbody>";
              echo "</table>";
            ?>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <input type="hidden" name="id_pedido" value="<?php echo $pedido->ID_PEDIDO; ?>">
                <input type="hidden" name="estadoactual" value="<?php echo $pedido->ESTADO; ?>">
                <button class="btn btn-lg btn-block color_use text-warning" title="Editar" type="submit" name="btn_editar_pedido"><i class='fas fa-edit'></i></button>
              </div> 
            </div>
            <?php
              if($_SESSION['PERMISSION'] == 1 && $pedido->ESTADO == 0){
                echo'<div class="form-row">
                      <div class="form-group col-md-12">
                        <a data-toggle="modal" data-target="#eliminarPedido" title="Eliminar" class="btn btn-danger btn-lg btn-block"><i class="fas fa-trash-alt"></i></a>
                      </div>
                    </div>';
              }
            ?>            
        </form>
        <div class="container d-flex  justify-content-around mt-2">
          <form method="POST" action="pedido_buscar.php">
            <input type="hidden" value="" name="inputSearch">
            <input type="hidden" value="" name="searchFilter">
            <input type="hidden" value="" name="searchActivity">
            <button type="submit" name="searchBtn" class="btn color_text bg-warning"><i class="fas fa-arrow-left"></i></button>
          </form>
          <button class="btn color_text bg-warning" name="lupa-lista" title="Buscar" data-toggle="modal" data-target="#listaPedidos"><i class="fas fa-search"></i></button>
        </div>
      </div>
    </div> 
  </div>
        
      
    <!--/# contenido pagina-->
    <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Script aparezca la sidebar -->
  <script type='text/javascript' src='js/toggle.js'></script>

</body>

</html>