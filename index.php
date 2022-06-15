<?php

    define("PROJECT_ROOT_PATH", __DIR__);
    require_once(PROJECT_ROOT_PATH."/vendor/autoload.php");

    //$uriOffset = 2;       //  "http://tiendaonline.com/api/..." 
    $uriOffset = 2;         //  "http://localhost/tiendaonline/api/..."

    $full_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $full_uri = explode('/', $full_uri);

    if (isset($full_uri[$uriOffset]) && $full_uri[$uriOffset] == "api") 
    {
        $endPoint = strtolower($full_uri[$uriOffset+1]);
        $uri = [];
        for ($key = $uriOffset+2; $key < count($full_uri); $key++)
            array_push($uri, $full_uri[$key]);
        require_once(PROJECT_ROOT_PATH."/rest-api/handle-request.php");     //ACCESO A LA API//
    }
    else
        require_once(PROJECT_ROOT_PATH."/paginas/index.php");               //ACCESO AL WEB SERVER//

?>