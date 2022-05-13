<?php

    class VendedorController extends ApiController
    {
        /**
         * Parsear la peticion, y ejecutar el metodo deseado
         */
        public function handle_request($uri)
        {
            switch ($uri[0]) 
            {
                //  "/vendedor/list"  //
                case 'list':
                    $this->list();
                    break;
                
                //  "/vendedor/..."  //
                default:
                    if (!isset($uri[1]) || $uri[1] == "") $this->enviarRespuesta("No existe el endpoint solicitado.", 404);
                    
                    //  "/vendedor/.../..."  //
                    switch ($uri[1]) 
                    {
                        //  "/vendedor/.../productos"  //
                        case 'productos':
                            if (!isset($uri[2]) || $uri[2] == "") $this->enviarRespuesta("No existe el endpoint solicitado.", 404);
                            
                            //  "/vendedor/.../productos/..."  //
                            switch ($uri[2]) 
                            {
                                //  "/vendedor/.../productos/list"  //
                                case 'list':
                                    $this->list_productos($uri[0]);
                                    break;

                                //  "/vendedor/.../productos/..."  //
                                default:
                                    $this->enviarRespuesta("No existe el endpoint solicitado.", 404);
                                    break;
                            }
                            break;

                        default:
                            $this->enviarRespuesta("No existe el endpoint solicitado.", 404);
                            break;
                    }
                    break;
            }
        }

        /**
         * "/vendedor/1234/productos/list" Endpoint - Listar los vendedores
         */
        public function list_productos($idVendedor)
        {
            $this->checkRequestMethod('POST');

            try 
            {
                //Procesar la salida de datos//
                $vendedor = new Vendedor($idVendedor);
                $productos = $vendedor->getProductos();
                $responseData = json_encode($productos);
                $this->enviarRespuesta($responseData, 200);
            } 
            catch (Error $e) 
            {
                $this->enviarRespuesta('Algo salio mal! Por favor contacte al soporte. '.$e->getMessage(), 500);
            }
        }

        /**
         * "/vendedor/list" Endpoint - Listar los vendedores
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