<?php

    class OrdenesController extends ApiController
    {
        /**
         * Parsear la peticion, y ejecutar el metodo deseado
         */
        public function handle_request($uri)
        {
            switch ($uri[0]) 
            {
                //case 'list'     : $this->get_ordenes(); break;
                case 'add'     : $this->add_orden(); break;
                default         : $this->enviarRespuesta("No existe el endpoint solicitado.", 404); break;
            }
        }

        /**
         * "/ordenes/add" Endpoint - Listar productos de un vendedor
         */
        public function add_orden()
        {
            $this->checkRequestMethod('POST');

            if(!isset($_POST["items"]) || !isset($_POST["vendedor_id"]) ||  !isset($_POST["comprador_id"]))
                $this->enviarRespuesta('Se esperaba el parametro POST items, vendedor_id y comprador_id', 400);

            $items = json_decode($_POST["items"]);
            $id_vendedor = $_POST["vendedor_id"];
            $id_comprador = $_POST["comprador_id"];
            
            try 
            {
                //Procesar la salida de datos//
                $orden = Orden::createOrden($id_comprador, $id_vendedor, $items);
                $this->enviarRespuesta('Orden creada con id: '.$orden->id, 200);
            } 
            catch (Error $e) 
            {
                $this->enviarRespuesta('Algo salio mal! Por favor contacte al soporte. '.$e->getMessage(), 500);
            }
        }
    }

?>