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
  <title>Proveedores</title>

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
      $sql = $conexion->prepare("SELECT * FROM PROVEEDORES WHERE ID_PROVEEDOR = ?;");
      $sql ->execute([$id]);
      $proveedor = $sql->fetch(PDO::FETCH_OBJ);
    ?>
    <!--/#PHP-->
    <!--PHP EDITAR PROVEEDOR-->
     
    <?php
      if(isset($_POST['btn_editar_proveedor'])){
        $conexion->exec("SET CHARACTER SET utf8");

        $id_input = $_POST['id_input'];
        $id2 = $_POST['id2'];
        $nombre_p2 = strtoupper($_POST['nombre_proveedor2']);
        $nombre_c2 = strtoupper($_POST['nombre_contacto2']);
        $provincia2 = strtoupper($_POST['provincia2']);
        $ciudad2 = strtoupper($_POST['ciudad2']);
        $telefono2 = $_POST['telefono2'];
        $email2 = strtolower($_POST['email2']);
        $estado2 = $_POST['estado2'];
        $optionestado2 = intval($estado2);
          
        if($id_input != $id2){
          $sql = $conexion->prepare("UPDATE PROVEEDORES SET ID_PROVEEDOR = ?, NOMBRE_PROVEEDOR = ?, NOMBRE_CONTACTO = ?, PROVINCIA = ?, CIUDAD = ?, TELEFONO = ?, EMAIL = ?, ESTADO = ? WHERE ID_PROVEEDOR = ?;");
          $resultado = $sql->execute([$id_input,$nombre_p2,$nombre_c2,$provincia2,$ciudad2,$telefono2,$email2,$optionestado2,$id2]);
          /** Se necesita refrescar la página manualmente ya que sino no se ve la lista actualizada */
            
          if($resultado === TRUE){
            echo "<script>alert('¡Proveedor editado correctamente!')</script>";
            echo "<script>window.location.href = 'proveedor_editar.php?id=$id_input';</script>"; 
                                
          }else{
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Ya existe Proveedor con ese ID.</strong> Compruebe los campos.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
          } 
        }else if($id_input === $id2){     
          $sql = $conexion->prepare("UPDATE PROVEEDORES SET NOMBRE_PROVEEDOR = ?, NOMBRE_CONTACTO = ?, PROVINCIA = ?, CIUDAD = ?, TELEFONO = ?, EMAIL = ?, ESTADO = ? WHERE ID_PROVEEDOR = ?;");
          $resultado = $sql->execute([$nombre_p2,$nombre_c2,$provincia2,$ciudad2,$telefono2,$email2, $optionestado2,$id2]);

          $sql = $conexion->prepare("SELECT * FROM PEDIDOS WHERE ID_PROVEEDOR = ?");
          $identabla = $sql->execute([$id_input]);
          if($identabla === TRUE){
            $sql = $conexion->prepare("UPDATE PEDIDOS SET NOMBRE_PROVEEDOR = ? WHERE ID_PROVEEDOR = ?;");
            $resultado = $sql->execute([$nombre_p2,$id2]);
          }
          if($resultado === TRUE){
            echo "<script>alert('¡Proveedor editado correctamente!')</script>";
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
    <!--/#PHP EDITAR PRODUCTO-->
    <!--modal eliminar producto-->
    <div class="modal fade" id="eliminarProveedor" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="eliminarProveedorLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="eliminarProveedorLabel">¿Desea eliminar?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Esta acción no se puede deshacer.</p>
          </div>
          <div class="modal-footer">              
            <a href="proveedor_eliminar.php?id=<?php echo $proveedor->ID_PROVEEDOR;?>" type="button" class='btn btn-outline-danger bg-danger text-white'><i class='fas fa-trash-alt'></i></a>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i></button>
          </div>
        </div>
      </div>
    </div>
    <!--/modal eliminar proveedor-->  
    <!--modal lista de proveedores-->
    <div class="modal" id="listaProveedores" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="close_sessionLabel" aria-hidden="true">
      <div class="text-center" style="width:80%;margin: 30px auto;">
        <div class="modal-content">
          <div class="modal-header">
            <img id="img_modal" src="img/proveedores.png">
              <div class="container d-flex justify-content-center mb-3">
                <form class="form" action="proveedor_buscar.php" method="post">
                  <div class="container-fluid row">
                    <div class="col-6 p-0">
                      <select class="custom-select mb-1 color_font" name="searchFilter">
                        <option value="ID_PROVEEDOR">ID</option>
                        <option value="NOMBRE_PROVEEDOR">Proveedor</option>
                        <option value="NOMBRE_CONTACTO">Contacto</option>
                        <option value="PROVINCIA">Provincia</option>
                        <option value="CIUDAD">Ciudad</option>
                        <option value="TELEFONO">Teléfono</option>
                        <option value="EMAIL">Email</option>
                      </select>
                    </div>
                    <div class="col-6 p-0">
                      <select class="custom-select mb-1 color_font" name="searchActivity">
                        <option value="" selected>--</option>
                        <option value="1">ACTIVO</option>
                        <option value="0">INACTIVO</option>
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
                include_once 'conexion2.php';
                $consulta = "SELECT * FROM PROVEEDORES";
                $res = mysqli_query($conexion2, $consulta);
                  echo '<div class="container">';
                    echo '<table class="table table-striped table-hover color_font" id="tabla_modal">';
                      echo '<thead>';
                        echo "<tr>";
                          echo "<th>ID</th>";
                          echo "<th>Proveedor</th>";
                          echo "<th>Contacto</th>";
                          echo "<th>Provincia</th>";
                          echo "<th>Ciudad</th>";
                          echo "<th>Teléfono</th>";
                          echo "<th>Email</th>";
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
                          echo "<td>".$linea['ID_PROVEEDOR']."</td>";
                          echo "<td>".$linea['NOMBRE_PROVEEDOR']."</td>";
                          echo "<td>".$linea['NOMBRE_CONTACTO']."</td>";
                          echo "<td>".$linea['PROVINCIA']."</td>";
                          echo "<td>".$linea['CIUDAD']."</td>";
                          echo "<td>".$linea['TELEFONO']."</td>";
                          echo "<td>".$linea['EMAIL']."</td>";
                          if($linea['ESTADO']==0){
                            echo "<td>INACTIVO</td>";
                          }else{
                            echo "<td>ACTIVO</td>";
                          }
                          echo "<td><a href='proveedor_editar.php?id=".$linea['ID_PROVEEDOR']."'><button title='Editar' class='btn color_use text-warning'><i class='fas fa-edit'></i></button></a></td>";
                          $idproveedor = $linea['ID_PROVEEDOR'];
                          $queryEsta = $conexion->query("SELECT * FROM PEDIDOS WHERE ID_PROVEEDOR = '$idproveedor'");
                          $btnEliminar = $queryEsta->fetchALL(PDO::FETCH_OBJ);
                          if($_SESSION['PERMISSION'] == 1 && !$btnEliminar){
                            echo "<td><a href='proveedor_eliminar.php?id=".$linea['ID_PROVEEDOR']."'><button title='Eliminar' class='btn btn-outline-danger bg-danger text-white'><i class='fas fa-trash-alt'></i></button></a></td>";
                          }else{
                            echo '<td></td>';
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
        <img src="img/proveedores.png">
      </div>
      <h3 class="text-center mb-5">Proveedor <?php echo $proveedor->ID_PROVEEDOR?>:</h3>
      <form method="POST" action="" role="form">                  
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>ID Proveedor:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text color_font"><i class="fas fa-key"></i></span>
              </div>
              <input type="text" class="form-control color_font" autocomplete="off" value="<?php echo $proveedor->ID_PROVEEDOR?>" minlength="5" maxlength="5" pattern="^(?:[0-9][0-9]*|0)$" <?php include_once 'conexion2.php'; $id_proveedor = $_GET['id']; $consulta = "SELECT * FROM PEDIDOS WHERE ID_PROVEEDOR = $id_proveedor"; $res = mysqli_query($conexion2, $consulta); if(mysqli_num_rows($res)){ echo 'disabled';}else{ echo 'name="id_input"';}?>>
            </div>
          </div>
          <div class="form-group col-md-6">
            <label for="nombre_proveedor2">Nombre proveedor:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text color_font"><i class="fas fa-user-plus"></i></span>
              </div>
              <input type="text" class="form-control color_font" name="nombre_proveedor2" value="<?php echo $proveedor->NOMBRE_PROVEEDOR?>" maxlength="100" maxlength="50" autocomplete="off" required>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="nombre_contacto2">Nombre contacto:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text color_font"><i class="fas fa-user-tie"></i></span>
              </div>
              <input type="text" class="form-control color_font" name="nombre_contacto2" value="<?php echo $proveedor->NOMBRE_CONTACTO?>" maxlength="50" autocomplete="off" required>
            </div>
          </div>  
          <div class="form-group col-md-6">
            <label for="telefono2">Teléfono:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text color_font"><i class="fas fa-phone"></i></span>
              </div>
              <input type="text" class="form-control color_font" name="telefono2" value="<?php echo $proveedor->TELEFONO?>" minlength="9" maxlength="9" pattern="^(?:[1-9][0-9]*|0)$" autocomplete="off" required>
            </div>
          </div>      
        </div>
        <div class="form-row">     
          <div class="form-group col-md-6">
            <label for="provincia2">Provincia:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text color_font"><i class="fas fa-flag"></i></span>
              </div>
              <input type="text" class="form-control color_font" name="provincia2" value="<?php echo $proveedor->PROVINCIA?>" maxlength="100" autocomplete="off" required>
            </div>
          </div>                     
          <div class="form-group col-md-6">
            <label for="ciudad2">Ciudad:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text color_font"><i class="fas fa-city"></i></span>
              </div>
              <input type="text" class="form-control color_font" name="ciudad2" value="<?php echo $proveedor->CIUDAD?>" maxlength="100" autocomplete="off" required>
            </div>
          </div>                       
        </div>
        <div class="form-row">                       
          <div class="form-group col-md-6">
            <label for="email2">Email:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text color_font"><i class="fas fa-at"></i></span>
              </div>
              <input type="text" class="form-control color_font" name="email2" value="<?php echo $proveedor->EMAIL?>" maxlength="100" pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" autocomplete="off" required>
            </div>
          </div>            
          <div class="form-group col-md-6">
            <label for="estado2">Estado:</label>
            <div class="input-group">
              <div class="input-group-prepend">
              <span class="input-group-text color_font"><i class="fas fa-power-off"></i></span>
            </div>
            <select class="custom-select color_font" name="estado2">                     
              <option value='1'>ACTIVO</option>
              <option <?php if($proveedor->ESTADO == 0){ echo 'selected'; } ?> value='0'>INACTIVO</option>
            </select>
            </div>
          </div>    
        </div>
        <div class="form-row">
          <div class="form-group col-md-12">
            <?php include_once 'conexion2.php'; $id_proveedor = $_GET['id']; $consulta = "SELECT * FROM PEDIDOS WHERE ID_PROVEEDOR = $id_proveedor"; $res = mysqli_query($conexion2, $consulta); if(mysqli_num_rows($res)){echo '<input type="hidden" name="id_input" value="'.$proveedor->ID_PROVEEDOR.'">';}?> 
            <input type="hidden" name="id2" value="<?php echo $proveedor->ID_PROVEEDOR;?>">
            <button class="btn btn-lg btn-block color_use text-warning" title="Editar" type="submit" name="btn_editar_proveedor"><i class='fas fa-edit'></i></button>
          </div> 
        </div>
        <?php
          $queryEsta = $conexion->query("SELECT * FROM PEDIDOS WHERE ID_PROVEEDOR = '$proveedor->ID_PROVEEDOR'");
          $btnEliminar = $queryEsta->fetchALL(PDO::FETCH_OBJ);
          if($_SESSION['PERMISSION'] == 1 && !$btnEliminar){
            echo '<div class="form-row">
                    <div class="form-group col-md-12">
                      <a data-toggle="modal" data-target="#eliminarProveedor" title="Eliminar" class="btn btn-danger btn-lg btn-block"><i class="fas fa-trash-alt"></i></a>
                    </div>
                  </div>';
          } 
        ?>
      </form>
      <div class="container d-flex  justify-content-around mt-2">
        <form method="POST" action="proveedor_buscar.php">
          <input type="hidden" value="" name="inputSearch">
          <input type="hidden" value="" name="searchFilter">
          <input type="hidden" value="" name="searchActivity">
          <button type="submit" name="searchBtn" class="btn color_text bg-warning"><i class="fas fa-arrow-left"></i></button>
        </form>
        <button class="btn color_text bg-warning" title="Buscar" name="lupa-lista" data-toggle="modal" data-target="#listaProveedores"><i class="fas fa-search"></i></button>
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