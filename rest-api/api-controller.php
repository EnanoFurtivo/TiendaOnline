<?php

    require_once(PROJECT_ROOT_PATH."/modelo/modelo-controller.php");

    class ApiController extends Database
    {
        /**
         * __call Atrapar llamadas a metodos inexistentes.
         */
        public function __call($name, $arguments)
        {
            $this->enviarRespuesta("No existe el end point requerido.", 501);
        }
    
        /**
         * Obtener parametros GET.
         * 
         * @return array
         */
        protected function getQueryStringParams()
        {
            $queryParams = null;
            parse_str($_SERVER['QUERY_STRING'], $queryParams);
            return $queryParams;
        }
    
        /**
         * Enviar respuesta al cliente.
         *
         * @param string $mensaje Server response
         * @param int $responseCode Http response code
         */
        public static function enviarRespuesta($mensaje, $responseCode)
        {
            header_remove('Set-Cookie');
            header('Content-Type: application/json');

            if ($responseCode && $responseCode >= 300)
                $mensaje = '{ "error_code": "'.$responseCode.'", "error_message": "'.$mensaje.'" }';

            echo $mensaje;
            http_response_code($responseCode);
            die();
        }
    }

    if (!isset($uri[$uriOffset+2]) || $uri[$uriOffset+2] == "")
        ApiController::enviarRespuesta("Se esperaba clase y metodo para acceder a la api.", 400);

    $endPoint = strtolower($uri[$uriOffset+1]);
    $apiFileName = $endPoint.".php";
    $apiFilePath = PROJECT_ROOT_PATH."/rest-api/".$endPoint.".php";
    $modelControllerClass = $uri[$uriOffset+1].'Controller';
    
    //Incluir el archivo de la clase controladora//
    if (!file_exists($apiFilePath))
        ApiController::enviarRespuesta("No existe el archivo asociado al end point requerido.", 404);
    
    require($apiFilePath);

    //ERORR INTERNO DE PROGRAMACION, NO DEBERIA OCURRIR//
    if (!class_exists($modelControllerClass))
        ApiController::enviarRespuesta("No existe la clase '".$modelControllerClass."' correspondiente al point requerido.", 501);
    
    //Crear instancia de la clase controladora que corresponda y ejecutar el metodo requerido//
    $strMethodName = $uri[$uriOffset+2];
    $objController = new $modelControllerClass();
    $objController->{$strMethodName}();

?>