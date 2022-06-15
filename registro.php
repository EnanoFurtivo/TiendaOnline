<?php
    $error = "";
    if (isset($_POST["sent"]))
    {
        $has_username = isset($_POST["username"]) && $_POST["username"] != "";
        $has_email = isset($_POST["email"]) && $_POST["email"] != "";
        $has_telefono = isset($_POST["telefono"]) && $_POST["telefono"] != "";
        $has_password = isset($_POST["password"]) && $_POST["password"] != "";
        $has_reppassword = isset($_POST["reppassword"]) && $_POST["reppassword"] != "";

        if ($has_username && $has_email && $has_password && $has_reppassword && $has_telefono)
        {
            $username = $_POST["username"];
            $email = $_POST["email"];
            $telefono = $_POST["telefono"];
            $password = $_POST["password"];
            $rep_password = $_POST["reppassword"];
            $tipo_usuario = $_POST["tipousr"];

            if(!defined('PROJECT_ROOT_PATH'))
                define("PROJECT_ROOT_PATH", dirname( __FILE__, 1 ));

            require_once(PROJECT_ROOT_PATH.'/vendor/autoload.php');
            require_once(PROJECT_ROOT_PATH.'/modelo/load-modelo.php');

            $resultSet = Database::select("SELECT ID_USUARIO FROM usuario WHERE USERNAME = ?", [$username]);
            if (empty($resultSet))
            {
                if ($password == $rep_password)
                {
                    $passwordHash = hash('sha256', $password);
                    $passwordEncrypted = Encryption::encrypt($passwordHash);
                    Database::insert("INSERT INTO usuario(USERNAME, PASSWORD, MAIL, TELEFONO, TIPO_USUARIO) VALUES (?,?,?,?,?)", [$username, $passwordEncrypted, $email, $telefono, $tipo_usuario]);
		            header('Location: http://'.$_SERVER['HTTP_HOST'].'/tiendaonline/login.php');
                }
                else
                    $error = "Las contraseñas deben coincidir";
            }
            else
                $error = "Ya existe un usuario con username: '".$username."'";
        }
        else
        {
            $error = "Se deben incluir los siguientes datos:";
            $error .= ($has_username) ? "" : ", nombre de usuario";
            $error .= ($has_email) ? "" : ", email";
            $error .= ($has_telefono) ? "" : ", telefono";
            $error .= ($has_password) ? "" : ", contraseña";
            $error .= ($has_reppassword) ? "" : ", repeticion de contraseña";
        }
    }
?>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Home">
        <meta name="author" content="Luciano Godoy">
        <meta name="theme-color" content="#7952b3">
        <title>TiendaOnline login</title>

        <!-- jQuery; Popper -->
        <script src="librerias/jquery-3.6.0.js"></script>
        <script src="librerias/popper.min.js"></script>

        <!-- Bootstrap -->
        <script src="librerias/bootstrap/js/bootstrap.js"></script>
        <link rel="stylesheet" href="librerias/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="librerias/bootstrap/css/bootstrap-utilities.css">
        <link rel="stylesheet" href="librerias/bootstrap/icons/bootstrap-icons.css">
    </head>

    <body>
        <link rel="stylesheet" href="login.css">

        <main class="form-signin text-center">
            <form action="registro.php" method="post">
                <a class="sidebar-title d-flex flex-shrink-0 text-light text-decoration-none mb-4 align-items-center justify-content-center">
                    <image class="me-2" id="logo" width="42" height="42" x="0" y="0" src="assets/logo.png"/>
                    <p class="login-title h1">TiendaOnline</p>
                </a>

                <input type="text" class="form-control" id="login-sent" placeholder="sent" name="sent" value="sent" hidden>

                <div class="form-floating">
                    <input type="text" class="form-control straight-bottom" id="login-username" placeholder="usuario" name="username">
                    <label for="login-username">Nombre de usuario</label>
                </div>

                <div class="form-floating">
                    <input type="email" class="form-control straight-top straight-bottom" id="login-email" placeholder="name@example.com" name="email">
                    <label for="login-email">Email</label>
                </div>
                
                <div class="form-floating mb-3">
                    <input type="text" class="form-control straight-top" id="login-telefono" placeholder="Telefono" name="telefono">
                    <label for="login-telefono">Telefono</label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control straight-bottom" id="login-password" placeholder="Contraseña" name="password">
                    <label for="login-password">Contraseña</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control straight-top" id="login-reppassword" placeholder="Repetir contraseña" name="reppassword">
                    <label for="login-reppassword">Repita la contraseña</label>
                </div>

                    <select class="form-select mb-3" id="login-combobox" placeholder="reppassword" name="tipousr">
                        <option value="comprador" selected>comprador</option>
                        <option value="vendedor">vendedor</option>
                    </select>

                <button class="w-100 btn btn-lg btn-primary" type="submit">Crear cuenta</button>
                
                <p class="mt-5 mb-3" style="color: #FF0000; "><?php echo $error; ?></p>
                <p class="mt-5 mb-3 text-muted">~ TiendaOnline 2022 ~</p>
                <p style="color: #FFFFFF; ">Ya tiene una cuenta? <a href="login.php">Iniciar Sesión</a></p>

            </form>

        </main>

    </body>
</html>