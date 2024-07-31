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
  <title>Tiendas</title>

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


    <!--PHP LISTAR TIENDAS-->
      <?php   
        include 'conexion.php';      
        $sql = $conexion->query("SELECT * FROM TIENDAS;");
        $resultado = $sql->fetchALL(PDO::FETCH_OBJ);
      ?>                  
    <!--/PHP LISTAR TIENDAS-->
    <!--modal lista de tiendas-->
    <div class="modal" id="listaTiendas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="listaTiendasLabel" aria-hidden="true">
      <div class="text-center" style="width:80%;margin: 30px auto;">
        <div class="modal-content">
          <div class="modal-header justify-content-around">
            <img id="img_modal" src="img/tiendas.png">  
            <div class="container d-flex justify-content-center">
              <form class="form" action="tienda_buscar.php" method="post">
                <div class="container-fluid row">
                  <div class="col-6 p-0">
                    <select class="custom-select mb-1 color_font" name="searchFilter">
                      <option value="ID_TIENDA">ID</option>
                      <option value="NOMBRE_TIENDA">Tienda</option>
                      <option value="RESPONSABLE">Responsable</option>
                      <option value="TELEFONO">Teléfono</option>
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
            <div class="container">
              <table class="table table-striped table-hover color_font" id="tabla_modal">
                <thead>
                  <tr class="font-weight-bold">
                    <th>ID</th>
                    <th>Tienda</th>
                    <th>Responsable</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th></th>
                    <?php
                      if($_SESSION['PERMISSION'] == 1){
                        echo "<th></th>";   
                      } 
                    ?>
                  </tr>
                </thead>
                <tbody>
                <?php
                  foreach ($resultado as $datos){
                ?>
                  <tr>
                    <td><?php echo $datos->ID_TIENDA;?></td>
                    <td><?php echo $datos->NOMBRE_TIENDA;?></td>
                    <td><?php echo $datos->RESPONSABLE;?></td>
                    <td><?php echo $datos->TELEFONO;?></td>
                    <td><?php if($datos->ESTADO == 0){echo 'INACTIVO';}else{echo 'ACTIVO';};?></td>
                    <td><a href="tienda_editar.php?id=<?php echo $datos->ID_TIENDA;?>"><button title="Editar" class="btn color_use text-warning"><i class="fas fa-edit"></i></button></a></td>
                    <?php
                      $queryEsta = $conexion->query("SELECT * FROM PEDIDOS WHERE ID_TIENDA = '$datos->ID_TIENDA'");
                      $btnEliminar = $queryEsta->fetchALL(PDO::FETCH_OBJ);
                            
                      if($_SESSION['PERMISSION'] == 1 &&  !$btnEliminar){
                        echo "<td><a href='tienda_eliminar.php?id=".$datos->ID_TIENDA."'><button title='Eliminar' class='btn btn-outline-danger bg-danger text-white'><i class='fas fa-trash-alt'></i></button></a></td>";   
                      }else{
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
    <!--PHP añadir TIENDA-->
    <?php
      if(isset($_POST['btn_insert_tienda'])){
        $idTienda = strtoupper($_POST['id_tienda']);
        $nombre_tienda = strtoupper($_POST['nombre_tienda']);
        $responsable = strtoupper($_POST['responsable']);    
        $telefono = $_POST['telefono'];
        $estado = $_POST['estado'];
        $optionestado = intval($estado);
        
        $sql = $conexion->prepare("SELECT * FROM TIENDAS WHERE ID_TIENDA = ?");
        $sql->execute([$idTienda]);
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        if($resultado === FALSE){
          $sql = $conexion->prepare("INSERT INTO TIENDAS(ID_TIENDA,NOMBRE_TIENDA,RESPONSABLE,TELEFONO,ESTADO) VALUES(?,?,?,?,?);");

          $resultado = $sql->execute([$idTienda,$nombre_tienda,$responsable,$telefono,$optionestado]);     
          /** Se necesita refrescar la página manualmente ya que sino no se ve la lista actualizada */
          if($resultado === TRUE){
            echo  "<script type='text/javascript'>alert('Tienda añadida correctamente!');</script>";
            echo  "<script type='text/javascript' src='js/refrescar.js'></script>";
          } else {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Error al añadir.</strong> Compruebe los campos.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
          }
        }else{
          echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <strong>Ya existe Tienda con ese ID.</strong> Compruebe los campos.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                </div>';
          }
        } 
      ?>
    <!--/PHP AÑADIR TIENDA-->
    <!--form añadir tienda-->
      <div class="container my-5 color_font">       
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
          <div class="d-flex justify-content-center my-5">
            <img src="img/tiendas.png">
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="id_tienda">ID Tienda:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text color_font"><i class="fas fa-key"></i></span>
                </div>
                <input type="text" class="form-control color_font" name="id_tienda" autocomplete="off" minlength="5" maxlength="5" pattern="^(?:[0-9][0-9]*|0)$" required>
              </div>
            </div>
            <div class="form-group col-md-6">
              <label for="nombre_tienda">Nombre tienda:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text color_font"><i class="fa fa-shopping-bag"></i></span>
                </div>
                <input type="text" class="form-control color_font" name="nombre_tienda" maxlength="100" autocomplete="off" required>
              </div>
            </div>              
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="responsable">Responsable:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text color_font"><i class="fas fa-user-tie"></i></span>
                </div>
                <input type="text" class="form-control color_font" name="responsable" maxlength="45" autocomplete="off" required>
              </div>
            </div>
            <div class="form-group col-md-6">
              <label for="telefono">Teléfono:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text color_font"><i class="fas fa-phone"></i></span>
                </div>
                <input type="text" class="form-control color_font" name="telefono" autocomplete="off" minlength="5" maxlength="9" pattern="^(?:[1-9][0-9]*|0)$" required>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="estado">Estado:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text color_font"><i class="fas fa-power-off"></i></span>
                </div>
                <select class="custom-select color_font" name="estado">
                  <option value='1'>ACTIVO</option>
                  <option value='0'>INACTIVO</option>
                </select>
              </div>
            </div>
          </div>
          <button type="submit" name="btn_insert_tienda" title="Añadir" class="btn btn-lg btn-block color_use text-warning"><i class="fas fa-plus"></i></button>
        </form>
        <div class="container d-flex justify-content-around mt-2">
          <a href="tienda_home.php" class="btn color_text bg-warning"><i class="fas fa-arrow-left"></i></a>
          <button class="btn color_text bg-warning" name="lupa-lista" data-toggle="modal" title="Buscar" data-target="#listaTiendas"><i class="fas fa-search"></i></button>
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

</body>

</html>
