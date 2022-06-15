<?php
    define("PROJECT_ROOT_PATH", "../../..");
    require_once(PROJECT_ROOT_PATH.'/modelo/load-modelo.php');
    
    if (!isset($_POST["id_orden"]))
    {
        http_response_code(400);
        echo "No se envio id_orden con el llamado ajax";
        die();
    }

    $orden = new Orden($_POST["id_orden"]);

    $items;
    foreach ($orden->items as $key => $value) {
        $items[$key] = [ "producto" => new Producto($key), "cantidad" => $orden->items[$key]];
    }

    $orden->items = $items;
    echo json_encode($orden);
?>