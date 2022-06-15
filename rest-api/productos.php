<?php

    class ProductosController extends ApiController
    {
        /**
         * Parsear la peticion, y ejecutar el metodo deseado
         */
        public function handle_request($uri)
        {
            switch ($uri[0]) 
            {
                case 'list'     : $this->get_productos(); break;
                default         : $this->enviarRespuesta("No existe el endpoint solicitado.", 404); break;
            }
        }

        /**
         * "/productos/list?id_vendedor=ID" Endpoint - Listar productos de un vendedor
         */
        public function get_productos()
        {
            $this->checkRequestMethod('POST');
            $params = $this->getQueryStringParams();

            if(!isset($params["id_vendedor"]) || empty($params["id_vendedor"]))
                $this->enviarRespuesta('Se esperaba el parametro GET ?id_vendedor=ID ', 400);

            $idVendedor = $params["id_vendedor"];

            try 
            {
                //Procesar la salida de datos//
                $vendedor = new Vendedor($idVendedor);
                $arrProductos = $vendedor->getProductos();
                $responseData = json_encode($arrProductos);
                $this->enviarRespuesta($responseData, 200);
            } 
            catch (Error $e) 
            {
                $this->enviarRespuesta('Algo salio mal! Por favor contacte al soporte. '.$e->getMessage(), 500);
            }
        }
    }

?>