<?php

    class ProductoController extends ApiController
    {
        /**
         * "/producto/list" Endpoint - Obtener lista de productos
         */
        public function list()
        {
            $this->checkRequestMethod('POST');
            $arrQueryStringParams = $this->getQueryStringParams();

            try 
            {
                $producto = new Producto();

                $intLimit = 50;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit'] > 0)
                    $intLimit = $arrQueryStringParams['limit'];

                //Procesar la salida de datos//
                $arrUsers = $producto->getProductos($intLimit);
                $responseData = json_encode($arrUsers);
                $this->enviarRespuesta($responseData, 200);
            } 
            catch (Error $e) 
            {
                $this->enviarRespuesta('Algo salio mal! Por favor contacte al soporte. '.$e->getMessage(), 500);
            }
        }
    }

?>