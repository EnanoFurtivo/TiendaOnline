<?php

	if (session_status() == PHP_SESSION_NONE)
		session_start();

    if(!defined('PROJECT_ROOT_PATH'))
        define("PROJECT_ROOT_PATH", dirname( __FILE__, 1 ));

    require_once(PROJECT_ROOT_PATH.'/vendor/autoload.php');
    require_once(PROJECT_ROOT_PATH.'/modelo/load-modelo.php');
	require_once(PROJECT_ROOT_PATH.'/componentes/cookies.php');

    function delete_session()
    {
        delete_cookie("username");
        delete_cookie("authtoken");
        $_SESSION["username"] = null;
        $_SESSION["authtoken"] = null;
    }

    $error = "";
	$authenticated  =   false;
	$cookie_timeout_days = 7;
	
	$current_host = $_SERVER['HTTP_HOST'];
	$login_file = '/tiendaonline/login.php';
	$home_file = '/tiendaonline';
	$current_file = $_SERVER['PHP_SELF'];

	$home_page = 'http://'.$current_host.$home_file;
	$login_page = 'http://'.$current_host.$login_file;
	$current_page = 'http://'.$current_host.$current_file;

	$redirect_url = $home_page;

	if (isset($_POST["redirect"]))
		$redirect_url = $_POST['redirect'];

	if ($current_page == $login_page)
		$redirect_url = $home_page;

    if (!isset($_POST["close_session"]))
    {
        $cookie_exists  = 	(function_exists('get_cookie') && get_cookie('username') != null && get_cookie('authtoken') != null);
        $session_exists = 	(isset($_SESSION["username"]) && isset($_SESSION["authtoken"]));
        $login_exists   = 	(isset($_POST["username"]) && isset($_POST["password"]));

        if ($login_exists)
        {
            $username = $_POST['username'];
            $password = hash('sha256', $_POST['password']);
            $save_cookie = isset($_POST['save']);
            
            $user_credentials = Database::select("SELECT PASSWORD, ID_USUARIO, TIPO_USUARIO FROM usuario WHERE USERNAME = ?", [ $username ]);
            if ($user_credentials != null)
            {
                $user_credentials = $user_credentials[0];

                $stored_password = Encryption::decrypt($user_credentials["PASSWORD"]);

                if ($password == $stored_password)
                {
                    $new_token = bin2hex(random_bytes(64));
                    $token_scaped = Database::scape($new_token);
                    Database::insert("INSERT INTO token(ID_USUARIO, AUTHTOKEN, DIAS_VALIDO) VALUES (?,?,?)",
                                    [$user_credentials["ID_USUARIO"], $token_scaped, $cookie_timeout_days]);

                    $_SESSION["username"] = $username;
                    $_SESSION["authtoken"] = $new_token;

                    delete_cookie("username");
                    delete_cookie("authtoken");

                    if ($save_cookie)
                    {
                        set_cookie("username", $username, $cookie_timeout_days);
                        set_cookie("authtoken", $new_token, $cookie_timeout_days);
                    }	

                    $nombreUsr = $username;
                    $tipoUsr = $user_credentials["TIPO_USUARIO"];
                    $idUsr = $user_credentials["ID_USUARIO"];
                    $authenticated = true;
                }
                else
                    $error = "La contraseña ingresada es incorrecta";
            }
            else
                $error = "No existe el usuario '".$username."'";

        }
        else if ($cookie_exists || $session_exists)
        {
            $username = $session_exists ? $_SESSION["username"] : get_cookie('username');
            $authtoken = $session_exists ? $_SESSION["authtoken"] : get_cookie('authtoken');

            $user_credentials = Database::select("SELECT ID_USUARIO, TIPO_USUARIO FROM usuario WHERE USERNAME = ?", [ $username ]);
            if ($user_credentials != null)
            {
                $user_credentials = $user_credentials[0];
                $result = Database::select("SELECT AUTHTOKEN FROM token WHERE ID_USUARIO = ?", [ $user_credentials["ID_USUARIO"] ]);

                $is_authtoken_present = false;
                foreach ($result as $key => $row)
                    if ($row["AUTHTOKEN"] == $authtoken)
                        $is_authtoken_present = true;

                if ($is_authtoken_present)
                {
                    $_SESSION["username"] = $username;
                    $_SESSION["authtoken"] = $authtoken;
                    
                    $nombreUsr = $username;
                    $tipoUsr = $user_credentials["TIPO_USUARIO"];
                    $idUsr = $user_credentials["ID_USUARIO"];
                    $authenticated = true;
                }
                else
                    delete_session();
            }
        }
    }
    else
        delete_session();

	if (!$authenticated)
	{
		//mostrar login//
	
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

            <body class="background">
                <link rel="stylesheet" href="login.css">

                <main class="form-signin text-center">
                    <form action="login.php" method="post">

                        <input type="text" class="form-control" name="redirect" value="<?php echo $redirect_url; ?>" hidden>
                        
                        <a class="sidebar-title d-flex flex-shrink-0 text-light text-decoration-none mb-4 align-items-center justify-content-center">
                            <image class="me-2" id="logo" width="42" height="42" x="0" y="0" src="assets/logo.png"/>
                            <p class="login-title h1">TiendaOnline</p>
                        </a>

                        <div class="form-floating">
                            <input type="text" class="form-control straight-bottom" id="login-username" placeholder="usuario" name="username">
                            <label for="login-username">Nombre de usuario</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control straight-top" id="login-password" placeholder="contraseña" name="password">
                            <label for="login-password">Contraseña</label>
                        </div>

                        <div class="checkbox mb-3">
                            <input type="checkbox" value="remember-me" id="login-checkbox" name="save"> 
                            <label for="login-checkbox" style="color:#FFF;">Recordar credenciales</label>
                        </div>
                        
                        <button class="w-100 btn btn-lg btn-primary" type="submit">Ingresar</button>

                        <p class="mt-5 mb-3" style="color: #FF0000; "><?php echo $error; ?></p>
                        <p class="mt-5 mb-3 text-muted">~ TiendaOnline 2022 ~</p>
                        <p style="color: #FFFFFF; ">No tiene una cuenta <a href="registro.php">Registrse</a></p>

                    </form>
                </main>

            </body>
        </html>
        <?php
		die();
	}
	else if ($login_exists)
    {
		header('Location: '.$redirect_url);
    }
?>