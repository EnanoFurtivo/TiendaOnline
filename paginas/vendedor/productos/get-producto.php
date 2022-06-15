<?php
    define("PROJECT_ROOT_PATH", "../../..");
    require_once(PROJECT_ROOT_PATH.'/modelo/load-modelo.php');
    
    if (!isset($_POST["id_producto"]))
    {
        http_response_code(400);
        echo "No se envio id_producto con el llamado ajax";
        die();
    }

    $producto = new Producto($_POST["id_producto"]);
    echo json_encode($producto);
?>