<?php

    require_once(PROJECT_ROOT_PATH."/rest-api/api-controller.php");

    class ProductoController extends ApiController
    {
        /**
         * "/producto/list" Endpoint - Obtener lista de productos
         */
        public function list()
        {
            $strErrorDesc = '';
            $strErrorCode = 0;
            
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $arrQueryStringParams = $this->getQueryStringParams();
    
            if (strtoupper($requestMethod) == 'POST') 
            {
                try 
                {
                    $producto = new Producto();
    
                    $intLimit = 50;
                    if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit'] > 0)
                        $intLimit = $arrQueryStringParams['limit'];

                    //Procesar la salida de datos//
                    $arrUsers = $producto->getProductos($intLimit);
                    $responseData = json_encode($arrUsers);
                } 
                catch (Error $e) 
                {
                    $strErrorDesc = $e->getMessage().'Algo salio mal! Por favor contacte al soporte.';
                    $strErrorCode = 500;
                }
            } 
            else 
            {
                $strErrorDesc = 'Metodo '.$_SERVER["REQUEST_METHOD"].' no admitido';
                $strErrorCode = 422;
            }
    
            //enviar salida//
            if (!$strErrorDesc) 
                $this->enviarRespuesta($responseData, 200);
            else
                $this->enviarRespuesta($strErrorDesc, $strErrorCode);
        }
    }

?>