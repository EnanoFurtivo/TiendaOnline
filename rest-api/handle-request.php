<?php

    require_once(PROJECT_ROOT_PATH."/rest-api/api-controller.php");

    if (!isset($endPoint) || $endPoint == "" || !isset($uri[0]) || $uri[0] == "")
        ApiController::enviarRespuesta("Se esperaba endpoint para acceder a la api.", 400);

    $apiFileName = $endPoint.".php";
    $apiFilePath = PROJECT_ROOT_PATH."/rest-api/".$endPoint.".php";
    $modelControllerClass = $endPoint.'Controller';

    //AUTENTIFICAR CLIENTE//
    $authtokenMissing = (!isset($_POST["auth_token"]) || $_POST["auth_token"] == "");
    $missingUsername = (!isset($_POST["username"]) || $_POST["username"] == "");
    $missingPassword = (!isset($_POST["password"]) || $_POST["password"] == "");
    $credentialsMissing = ($missingUsername || $missingPassword);
    $isRequestGenerarToken = ($endPoint == "usuarios" && $uri[0] == "generar_token");

    if ($authtokenMissing && !$isRequestGenerarToken)
        ApiController::enviarRespuesta("Se esperaba un token de acceso.", 400);
    else if ($authtokenMissing && $isRequestGenerarToken && ($credentialsMissing || !Usuario::validarCredenciales($_POST["username"], $_POST["password"])))
        ApiController::enviarRespuesta("Se esperaban credenciales del cliente validas.", 401);
    else if (!$authtokenMissing && !Usuario::validarToken($_POST["auth_token"]))
        ApiController::enviarRespuesta("Token invalido o expirado.", 401);

    //Incluir el archivo de la clase controladora//
    if (!file_exists($apiFilePath))
        ApiController::enviarRespuesta("No existe el archivo asociado al end point requerido.", 404);
    
    require($apiFilePath);

    //ERORR INTERNO DE PROGRAMACION, NO DEBERIA OCURRIR//
    if (!class_exists($modelControllerClass))
        ApiController::enviarRespuesta("No existe la clase '".$modelControllerClass."' correspondiente al end point requerido.", 501);
    
    //Crear instancia de la clase controladora que corresponda y ejecutar el metodo requerido//
    $objController = new $modelControllerClass();
    $objController->handle_request($uri);

?>