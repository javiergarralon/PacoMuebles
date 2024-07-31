<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--librerias y frameworks-->
    <script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link href="css/index.css" rel="stylesheet">
    <link rel="shortcut icon" href="/img/logo_up.png" />
    <title>Inicie sesión</title>
</head>

<body>
    <!--Centrar-->
    <div id="centrar">
        <!--Logo-->
        <div class="login_logo">
            <table class="table">
                <tbody>
                    <tr>
                        <td style="border-top:none">
                            <img class="mx-auto d-block img-fluid" src="img/paco_login.jpeg">                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--\Logo-->
        <!--Formulario-->
        <div class="container login_form">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6 borde bg-white">
                    <!--PHP Inicio de sesión-->
                    <?php
                        //si el formulario es enviado (buttonLog = submit)
                        if(isset($_POST['buttonLog'])){
                            session_start();
                            include 'conexion.php';
                            $usuario = strtolower($_POST['username']);
                            $contrasena = $_POST['password'];
                            try{
                                $sentencia = $conexion->prepare('SELECT * FROM USUARIOS WHERE USER = ? and PASSWORD = ?;');
                                $sentencia->execute([$usuario, $contrasena]);
                                $resultado = $sentencia->fetch(PDO::FETCH_OBJ);
                                //si no existe un usuario y contraseña así    
                                if($resultado === FALSE){
                                    echo '<div class="alert alert-danger text-center mt-2" role="alert">
                                            <strong>Fallo inicio de sesión.</strong>
                                        </div>';
                                //si es correcto
                                }elseif($resultado->PASSWORD === $contrasena && $resultado->USER == $usuario){
                                    //la sesión con el nombre del usuario
                                    $_SESSION['PERMISSION'] = $resultado->PERMISSION;
                                    $_SESSION['USER'] = $resultado->USER;                                
                                    //redirecciona a la página de inicio
                                    header('Location: inicio.php');
                                }else{
                                    echo '<div class="alert alert-danger text-center mt-2" role="alert">
                                            <strong>Fallo inicio de sesión.</strong>
                                        </div>';
                                }                            
                            }catch(PDOException $ex){
                                echo '<div class="alert alert-danger text-center mt-2" role="alert">
                                         <strong>Fallo inicio de sesión.</strong>
                                     </div>';
                            }   
                        }
                    ?>
                    <!--\PHP Inicio de sesión-->
                    <div class="col-md-12 my-3">
                        <!--action para que el php sea en la misma página-->
                        <form id="login-form" class="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                            <div class="form-group ">
                                <label for="username" class="color_use">Usuario:</label><br>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" name="username" class="form-control" autocomplete="off" placeholder="Usuario" required>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="password" class="color_use">Contraseña:</label><br>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-unlock"></i></span>
                                    </div>  
                                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                                </div>
                                <div class="form-group">
                                    <button style="color:#ffcc00; background:#0c0090" class="btn-block" type="submit" name="buttonLog"><i class="fas fa-sign-in-alt"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--\Formulario-->

    </div>
    <!--\Centrar-->
    <script>
        //animación cuando se entra a la página
        window.sr = ScrollReveal();
        sr.reveal('.login_logo',{duration:1000,distance:'300px',origin:'left'});
        sr.reveal('.login_form',{duration:1000,distance:'300px',origin:'right'});

        //Smooth scrolling: suaviza las transiciones
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
        });
    </script>
</body>

</html>