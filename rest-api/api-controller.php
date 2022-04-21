<?php

    require_once(PROJECT_ROOT_PATH."/modelo/load-modelo.php");

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
            exit();
        }

        /**
         * Valida el metodo de request.
         *
         * @param string $desiredMethod Metodo de request esperado
         */
        protected function checkRequestMethod($desiredMethod)
        {
            if (strtoupper($_SERVER["REQUEST_METHOD"]) != $desiredMethod) 
                $this->enviarRespuesta('Metodo '.$_SERVER["REQUEST_METHOD"].' no admitido', 422);
        }

    }

?>