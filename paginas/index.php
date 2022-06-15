<?php require('login.php'); ?>
<!doctype html>
<html lang="en">
    <head>

        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Home">
        <meta name="author" content="Luciano Godoy">
        <meta name="theme-color" content="#7952b3">
        <title>TiendaOnline home</title>

        <!-- jQuery; Popper; -->
        <script src="librerias/jquery-3.6.0.js"></script>
        <script src="librerias/popper.min.js"></script>

        <!-- Bootstrap -->
        <script src="librerias/bootstrap/js/bootstrap.js"></script>
        <link rel="stylesheet" href="librerias/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="librerias/bootstrap/css/bootstrap-utilities.css">
        <link rel="stylesheet" href="librerias/bootstrap/icons/bootstrap-icons.css">

        <!-- Componentes -->
        <link rel="stylesheet" href="componentes/form.css">
        <link rel="stylesheet" href="componentes/navbar.css">
        <link rel="stylesheet" href="componentes/sidebar.css">
        <link rel="stylesheet" href="componentes/table/table.css">
        <script language="javascript" src="componentes/table/table.js"></script>
        <script language="javascript" src="componentes/cookies/cookies.js"></script>

        <!-- Utilidades -->
        <script language="javascript" src="componentes/tryparse.js"></script>

    </head>

    <body>
        <input id="idUsr" value="<?php echo $idUsr ?>" hidden>
        <input id="tipoUsr" value="<?php echo $tipoUsr ?>" hidden>
        <?php require_once('paginas/body.php'); ?>
    </body>

</html>
