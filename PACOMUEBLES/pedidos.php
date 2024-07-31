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


    <!--PHP LISTAR PEDIDOS-->
      <?php      
        include 'conexion.php';
        $sql = $conexion->query("SELECT * FROM PEDIDOS ORDER BY FECHA_PEDIDO DESC;");
        $resultado = $sql->fetchALL(PDO::FETCH_OBJ);
      ?>                  
    <!--/PHP LISTAR PEDIDOS-->
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
                      <option value="NOMBRE_PRODUCTO">Proveedor</option>
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
          <div class="modal-body d-flex justify-content-center">
            <div class="container d-flex justify-content-center">
              <table class="table table-striped table-hover  color_font" id="tabla_modal">
                <thead>
                  <tr class="font-weight-bold">
                    <th>ID Pedido</th>
                    <th>Proveedor</th>
                    <th>Tienda</th>
                    <th>F. Pedido</th>
                    <th>F. Entrega</th>
                    <th>Estado</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  foreach ($resultado as $datos){
                ?>
                  <tr>
                    <td><?php echo $datos->ID_PEDIDO;?></td>
                    <td><?php echo $datos->NOMBRE_PROVEEDOR;?></td>
                    <td><?php echo $datos->NOMBRE_TIENDA;?></td>
                    <td><?php echo date("d/m/Y",strtotime($datos->FECHA_PEDIDO));?></td>
                    <td><?php $fecha_entrega = date("d/m/Y",strtotime($datos->FECHA_ENTREGA)); if($fecha_entrega == "01/01/1970"){$fecha_entrega = "";} echo $fecha_entrega;?></td>
                    <td><?php if($datos->ESTADO == 0){echo 'EN CAMINO';}else{echo 'ENTREGADO';};?></td>
                    <td><a href="pedido_editar.php?id=<?php echo $datos->ID_PEDIDO;?>"><button title="Editar" class="btn color_use text-warning"><i class="fas fa-edit"></i></button></a></td>
                    <?php
                      if($_SESSION['PERMISSION'] == 1 && $datos->ESTADO != 1){
                        echo "<td><a href='pedido_eliminar.php?id=".$datos->ID_PEDIDO."'><button title='Eliminar' class='btn btn-outline-danger bg-danger text-white'><i class='fas fa-trash-alt'></i></button></a></td>";   
                      }else if($_SESSION['PERMISSION'] == 1){
                        echo "<td></td>";
                      } 
                    ?>
                  </tr>
                <?php
                  }
                ?>    
                </tbody>                
              </table>
            </div>
          </div>
          <div class="modal-footer">              
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i></button>
          </div>
        </div>
      </div>
    </div>
    <!--/modal-->  


    <!--contenido pagina-->
    <!--PHP añadir PEDIDO-->
    <?php
      if(isset($_POST['btn_insert_pedido'])){
        include_once 'conexion.php';  
        $id_pedido = $_POST['id_pedido'];
        $id_proveedor = $_POST['proveedores'];
        $id_tienda = $_POST['tiendas'];
        try{
          //se comprueba que el id no esté ya en la bbdd
          $sql = $conexion->prepare("SELECT * FROM PEDIDOS WHERE ID_PEDIDO = ?");
          $sql->execute([$id_pedido]);
          $resultado = $sql->fetch(PDO::FETCH_OBJ);
          if($resultado === FALSE){
            /**Se busca el nombre del proveedor */
            $sentencia = $conexion->prepare('SELECT NOMBRE_PROVEEDOR FROM PROVEEDORES WHERE ID_PROVEEDOR = ?;');
            $sentencia->execute([$id_proveedor]);
            $resultado = $sentencia->fetch(PDO::FETCH_OBJ);
        
            $nombre_proveedor = $resultado->NOMBRE_PROVEEDOR;
            /**Se busca el nombre de la tienda */
            $sentencia = $conexion->prepare('SELECT NOMBRE_TIENDA FROM TIENDAS WHERE ID_TIENDA = ?;');
            $sentencia->execute([$id_tienda]);
            $resultado = $sentencia->fetch(PDO::FETCH_OBJ);
            
            $nombre_tienda = $resultado->NOMBRE_TIENDA;
        
            $n_productos = count($_POST['productos']);
            $fecha_pedido = $_POST['fecha_pedido'];
            $observaciones = $_POST['observaciones'];
            
            /**se crea el pedido */
            $sql = $conexion->prepare("INSERT INTO PEDIDOS(ID_PEDIDO, ID_PROVEEDOR, ID_TIENDA, NOMBRE_PROVEEDOR, NOMBRE_TIENDA, FECHA_PEDIDO, FECHA_ENTREGA, OBSERVACIONES, ESTADO) VALUES (?,?,?,?,?,?,?,?,?)");
            $resultado = $sql->execute([$id_pedido,$id_proveedor,$id_tienda,$nombre_proveedor,$nombre_tienda,$fecha_pedido,NULL, $observaciones,0]); 
            $precio_total = 0;
            //Se van insertando productos en la lista con el id de pedido
            for ($i = 0; $i < $n_productos; $i++){
              $sentencia = $conexion->prepare('SELECT NOMBRE_PRODUCTO FROM PRODUCTOS WHERE ID_PRODUCTO = ?;');
              $sentencia->execute([$_POST['productos'][$i]]);
              $resultado = $sentencia->fetch(PDO::FETCH_OBJ);
              $nombre_producto = $resultado->NOMBRE_PRODUCTO;
             
              $precio = intval($_POST['precio'][$i]);
              $iva = intval($_POST['iva'][$i]) / 100 * $precio;
              $precio = $precio + $iva;
              $cantidad = intval($_POST['cantidad'][$i]);
              $precioxproducto = $precio * $cantidad;
              $precio_total += $precioxproducto;

              $sentencia = $conexion->prepare('INSERT INTO PRODUCTOSxPEDIDOS(ID_PRODUCTO, ID_PEDIDO, NOMBRE_PRODUCTO,PRECIO_PRODUCTO, CANTIDAD, IVA,PRECIO) VALUES (?,?,?,?,?,?,?)');
              $resultado = $sentencia->execute([$_POST['productos'][$i],$id_pedido,$nombre_producto,$_POST['precio'][$i], $_POST['cantidad'][$i], $_POST['iva'][$i],$precioxproducto]);   
              
              

              $precio = 0;
              $precioxproducto = 0;
            }
            //se crea la factura con el mismo id del pedido
            $sql = $conexion->prepare("INSERT INTO FACTURAS(ID_FACTURA, ID_PEDIDO, FECHA_EMISION, IMPORTE_TOTAL) VALUES (?,?,?,?)");
            $resultado = $sql->execute([$id_pedido,$id_pedido,$fecha_pedido, $precio_total]);

            echo "<script>alert('¡Pedido hecho correctamente!')</script>";
            echo  "<script type='text/javascript' src='js/refrescar.js'></script>";
          }else{
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Ya existe Pedido con ese ID.</strong> Compruebe los campos.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
          }
        }catch(PDOException $ex){
          echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <strong>Producto duplicado.</strong> Compruebe los productos introducidos.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>';
        } 
      }   
    ?>
    <!--/PHP AÑADIR PEDIDO-->
    
    

    <!--form añadir PEDIDO-->
      <div class="container my-5 color_font">       
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
          <div class="d-flex justify-content-center my-5">
            <img src="img/pedidos.png">
          </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="id_pedido">ID Pedido:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text color_font"><i class="fas fa-key"></i></span>
                  </div>
                  <input type="text" class="form-control color_font" name="id_pedido" autocomplete="off" minlength="5" maxlength="5" pattern="^(?:[0-9][0-9]*|0)$" required>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label for="fecha_pedido">Fecha pedido:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text color_font"><i class="fas fa-calendar-day"></i></span>
                  </div>
                  <input type="date" class="form-control color_font" name="fecha_pedido" value="<?php echo date('Y-m-d'); ?>" autocomplete="off" required>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <!--PHP SELECT PROVEEDORES-->
                <?php      
                    include_once 'conexion.php';
                    $sql = $conexion->query("SELECT * FROM PROVEEDORES WHERE ESTADO = 1 ORDER BY ID_PROVEEDOR ASC;");
                    $resultado = $sql->fetchALL(PDO::FETCH_OBJ);
                ?>
                <!--/#SELECT PROVEEDORES-->
                <div class="form-group">
                  <label for="proveedores">Proveedor:</label>  
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text color_font"><i class="fas fa-user-plus"></i></span>
                    </div>       
                    <select class="custom-select color_font" name="proveedores" required>
                    <?php
                      foreach ($resultado as $datos){
                    ?>   
                      <option value=<?php echo $datos->ID_PROVEEDOR ?>><?php echo $datos->ID_PROVEEDOR.' - '.$datos->NOMBRE_PROVEEDOR ?></option>                
                    <?php
                      } 
                    ?>
                    </select>
                  </div>
               </div>
              </div>
              <div class="form-group col-md-6">
              <!--PHP SELECT TIENDAS-->
              <?php      
                include_once 'conexion.php';
                $sql = $conexion->query("SELECT * FROM TIENDAS WHERE ESTADO = 1 ORDER BY ID_TIENDA ASC;");
                $resultado = $sql->fetchALL(PDO::FETCH_OBJ);
              ?>
              <!--/#SELECT TIENDAS-->   
                <div class="form-group">
                  <label for="tiendas">Tienda:</label> 
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text color_font"><i class="fa fa-shopping-bag"></i></span>
                      </div>            
                      <select class="custom-select color_font" data-mdb-filter="true" name="tiendas" required>
                      <?php
                        foreach ($resultado as $datos){
                      ?>   
                        <option value=<?php echo $datos->ID_TIENDA ?>><?php echo $datos->ID_TIENDA.' - '.$datos->NOMBRE_TIENDA ?></option>                
                      <?php
                        } 
                      ?>
                      </select>
                    </div>
                </div>  
              </div> 
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="observaciones">Observaciones:</label>
                <textarea class="form-control color_font" name="observaciones" rows="2" maxlength="200"></textarea>
              </div>
            </div>
            <hr>
            <div class="form-row addsection">
              <!--PHP SELECT PRODUCTOS-->
            <?php      
              include_once 'conexion.php';
              $sql = $conexion->query("SELECT * FROM PRODUCTOS WHERE ESTADO = 1 ORDER BY ID_PRODUCTO ASC;");
              $resultado = $sql->fetchALL(PDO::FETCH_OBJ);
            ?>
              <!--/#SELECT PRODUCTOS-->
              <div class="form-group col-md-6">
                <div class="form-group">
                  <label for="productos">Producto:</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text color_font"><i class="fas fa-couch"></i></span>
                    </div>
                    <select class="custom-select color_font" name="productos[]" required>
                    <?php
                      foreach ($resultado as $datos){
                    ?>   
                      <option value=<?php echo $datos->ID_PRODUCTO ?>><?php echo $datos->ID_PRODUCTO.' - '.$datos->NOMBRE_PRODUCTO ?></option>                
                    <?php
                      } 
                    ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-2">
                <label for="cantidad">Cantidad:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                      <span class="input-group-text color_font"><i class="fas fa-boxes"></i></span>
                  </div>
                  <input type="text" class="form-control color_font" name="cantidad[]" pattern="^(?:[0-9][0-9]*|0)$" maxlength="4" autocomplete="off" required>
                </div>
              </div>
              <div class="form-group col-md-2">
                <label for="precio">Precio:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text color_font"><i class="fas fa-euro-sign"></i></span>
                  </div>
                  <input type="text" class="form-control color_font" name="precio[]" autocomplete="off" pattern="^[-+]?[0-9]*\.?[0-9]{1,2}$" maxlength="7" required>
                </div>
              </div>
              <div class="form-group col-md-2">
                <label for="estado">IVA:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text color_font"><i class="fas fa-percent"></i></span>
                  </div> 
                  <select class="custom-select color_font" name="iva[]">
                    <option value='21'>21%</option>
                    <option value='10'>10%</option>
                    <option value='4'>4%</option>
                  </select>
                </div>
              </div>
              <br>
            </div>            
            <button name="btn_insert_pedido" class="btn btn-lg btn-block color_use text-warning"><i class="fas fa-plus"></i></button>
          </form>
          <div class="container d-flex justify-content-around mt-2">
            <a href="pedido_home.php" class="btn color_text bg-warning"><i class="fas fa-arrow-left"></i></a>
            <button class="btn color_text bg-warning" title="Añadir otro producto" id="btn_add_producto"><i class="fas fa-cart-plus"></i></button>
            <button class="btn color_text bg-warning"  title="Buscar" name="lupa-lista" data-toggle="modal" data-target="#listaPedidos"><i class="fas fa-search"></i></button>
          </div>
        </div>
      </div>
    </div>
  <!--/#contenido de la pagina-->
  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Script aparezca la sidebar -->
  <script type='text/javascript' src='js/toggle.js'></script>

  <!-- add producto-->
  <script>
  $('document').ready(function() {
    $('#btn_add_producto').click(function(e) {
        e.preventDefault();
        $('.addsection').append('<div class="form-group col-md-6 color_font"> <div class="form-group"> <label for="productos">Producto:</label> <div class="input-group">  <div class="input-group-prepend"><span class="input-group-text color_font"><i class="fas fa-couch"></i></span></div> <select class="custom-select color_font" name="productos[]" required>  <?php  foreach ($resultado as $datos){ ?> <option value=<?php echo $datos->ID_PRODUCTO ?>><?php echo $datos->ID_PRODUCTO.' - '.$datos->NOMBRE_PRODUCTO ?></option>   <?php }  ?>  </select> </div> </div>  </div><div class="form-group col-md-2 color_font"> <label for="cantidad">Cantidad:</label><div class="input-group">  <div class="input-group-prepend"><span class="input-group-text color_font"><i class="fas fa-boxes"></i></span></div> <input type="text" class="form-control color_font" name="cantidad[]" pattern="^(?:[0-9][0-9]*|0)$" maxlength="4" autocomplete="off" required> </div> </div> <div class="form-group col-md-2 color_font">  <label for="precio">Precio:</label> <div class="input-group">  <div class="input-group-prepend"><span class="input-group-text color_font"><i class="fas fa-euro-sign"></i></span></div><input type="text" class="form-control color_font" name="precio[]" autocomplete="off" pattern="^[-+]?[0-9]*\.?[0-9]{1,2}$" maxlength="7" required> </div> </div> <div class="form-group col-md-2 color_font">  <label for="estado">IVA:</label> <div class="input-group">  <div class="input-group-prepend"><span class="input-group-text color_font"><i class="fas fa-percent"></i></span></div> <select class="custom-select color_font" name="iva[]">  <option value="21">21%</option>  <option value="10">10%</option> <option value="4">4%</option>  </select> </div> </div>    <br>');
    })
});
  </script>
</body>
</html>
