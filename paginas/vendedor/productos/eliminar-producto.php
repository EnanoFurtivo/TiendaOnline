<?php
    define("PROJECT_ROOT_PATH", "../../..");
    require_once(PROJECT_ROOT_PATH.'/modelo/load-modelo.php');
    
    if (!isset($_POST["id"]))
    {
        http_response_code(400);
        echo "No se envio sku con el llamado ajax";
        die();
    }

    $id = $_POST["id"];
    if (!Producto::deleteProducto($id))
    {
        http_response_code(400);
        echo "No se pudo eliminar el producto...";
        die();
    }

    echo "Producto eliminado con exito";
    http_response_code(200);
?>