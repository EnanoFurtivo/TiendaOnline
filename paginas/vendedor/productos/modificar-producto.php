<?php
    define("PROJECT_ROOT_PATH", "../../..");
    require_once(PROJECT_ROOT_PATH.'/modelo/load-modelo.php');
    
    if (!isset($_POST["id"]))
    {
        http_response_code(400);
        echo "No se envio id del producto con el llamado ajax";
        die();
    }

    $id = $_POST["id"];
    $producto = new Producto($id);
    if ($producto == null)
    {
        http_response_code(400);
        echo "No se pudo modificar el producto...";
        die();
    }

    $producto->setEscala($_POST["scale"]);
    $producto->setPrecio($_POST["precio"]);
    $producto->setStock($_POST["stock"]);
    
    echo "Producto modificado con exito";
    http_response_code(200);
?>