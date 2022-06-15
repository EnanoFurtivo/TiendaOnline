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
    if ($orden->estado == "finalizada")
    {
        http_response_code(400);
        echo "La orden se encuentra finalizada y no es posible cancelarla";
        die();
    }
    $orden->cancelar();
    
    http_response_code(200);
?>