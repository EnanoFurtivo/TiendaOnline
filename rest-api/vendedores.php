<?php

    class VendedoresController extends ApiController
    {
        /**
         * Parsear la peticion, y ejecutar el metodo deseado
         */
        public function handle_request($uri)
        {
            switch ($uri[0]) 
            {
                case 'list': $this->list(); break;
                default: $this->enviarRespuesta("No existe el endpoint solicitado.", 404); break;
            }
        }

        /**
         * "/vendedores/list" Endpoint - Listar los vendedores
         */
        public function list()
        {
            $this->checkRequestMethod('POST');

            try 
            {
                //Procesar la salida de datos//
                $arrVendedores = Vendedor::fetchVendedores();
                $responseData = json_encode($arrVendedores);
                $this->enviarRespuesta($responseData, 200);
            } 
            catch (Error $e) 
            {
                $this->enviarRespuesta('Algo salio mal! Por favor contacte al soporte. '.$e->getMessage(), 500);
            }
        }
    }

?>