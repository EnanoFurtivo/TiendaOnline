<?php

    define("PROJECT_ROOT_PATH", __DIR__);
    require_once(PROJECT_ROOT_PATH."/vendor/autoload.php");

    //$uriOffset = 1;       //  "http://tiendaonline.com/api/{clase}/{metodo}?{parametros}" 
    $uriOffset = 2;         //  "http://localhost/tiendaonline/api/{clase}/{metodo}?{parametros}"

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);

    if (isset($uri[$uriOffset]) && $uri[$uriOffset] == "api") 
        require_once(PROJECT_ROOT_PATH."/rest-api/handle-request.php");     //ACCESO A LA API//
    else
        require_once(PROJECT_ROOT_PATH."/paginas/index.php");               //ACCESO AL WEB SERVER//

?>