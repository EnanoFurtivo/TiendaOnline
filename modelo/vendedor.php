<?php

    class Vendedor extends Usuario
    {
        private $productos = array();

        public function __construct($id) {
            parent::__construct($id);
        }
         
        /** 
         * Obtener los productos de un vendedor.
         * 
         */
        public function fetchProductos() {
            $resultSet = $this->select("SELECT ID_PRODUCTO FROM producto WHERE ID_VENDEDOR = ?", [$this->id]);
            if(empty($resultSet))
                return null;

            $productos = array();
            foreach ($resultSet as $key => $value) {
                $id = $resultSet[$key]["ID_PRODUCTO"];
                array_push($productos, new Producto($id));
            }

            $this->productos = $productos;
        }

        /**
         * Actualizar ordenes del vendedor.
         * @return void
         */
        protected function fetchOrdenes() {
            $resultSet = $this->select("SELECT * FROM orden WHERE ID_VENDEDOR = ?", [$this->id]);
            if(empty($resultSet))
                return;
            
            $ordenes = array();
            foreach ($resultSet as $key => $value) {
                $idOrden = $resultSet[$key]["ID_ORDEN"];
                $ordenes[$idOrden] = new Orden($idOrden);
            }

            $this->ordenes = $ordenes;
        }

        //                     $$$$$$\ $$$$$$$$\  $$$$$$\ $$$$$$$$\ $$$$$$\  $$$$$$\  
        //                    $$  __$$\\__$$  __|$$  __$$\\__$$  __|\_$$  _|$$  __$$\ 
        //                    $$ /  \__|  $$ |   $$ /  $$ |  $$ |     $$ |  $$ /  \__|
        //                    \$$$$$$\    $$ |   $$$$$$$$ |  $$ |     $$ |  $$ |      
        //                     \____$$\   $$ |   $$  __$$ |  $$ |     $$ |  $$ |      
        //                    $$\   $$ |  $$ |   $$ |  $$ |  $$ |     $$ |  $$ |  $$\ 
        //                    \$$$$$$  |  $$ |   $$ |  $$ |  $$ |   $$$$$$\ \$$$$$$  |
        //                     \______/   \__|   \__|  \__|  \__|   \______| \______/  

        /** 
         * Obtener todos los vendedores.
         * 
         * @return array Vendedor[]
         */
        public static function fetchVendedores() {
            $resultSet = Database::select("SELECT ID_USUARIO FROM usuario WHERE TIPO_USUARIO = ?", ["vendedor"]);
            if(empty($resultSet))
                return null;

            $vendedores = array();
            foreach ($resultSet as $key => $value) {
                $id = $resultSet[$key]["ID_USUARIO"];
                array_push($vendedores, new Vendedor($id));// $vendedores[$id] = ;
            }

            return $vendedores;
        }

        //             $$$$$$\  $$$$$$$$\ $$$$$$$$\ $$$$$$$$\ $$$$$$$$\ $$$$$$$\   $$$$$$\  
        //            $$  __$$\ $$  _____|\__$$  __|\__$$  __|$$  _____|$$  __$$\ $$  __$$\ 
        //            $$ /  \__|$$ |         $$ |      $$ |   $$ |      $$ |  $$ |$$ /  \__|
        //            $$ |$$$$\ $$$$$\       $$ |      $$ |   $$$$$\    $$$$$$$  |\$$$$$$\  
        //            $$ |\_$$ |$$  __|      $$ |      $$ |   $$  __|   $$  __$$<  \____$$\ 
        //            $$ |  $$ |$$ |         $$ |      $$ |   $$ |      $$ |  $$ |$$\   $$ |
        //            \$$$$$$  |$$$$$$$$\    $$ |      $$ |   $$$$$$$$\ $$ |  $$ |\$$$$$$  |
        //             \______/ \________|   \__|      \__|   \________|\__|  \__| \______/ 

        /**
         * Obtener los productos del vendedor.
         * 
         * @return array|null
         */
        public function getProductos() {
            $this->fetchProductos();
            return $this->productos;
        }

        /**
         * Obtener un producto del vendedor.
         * 
         * @param int $id
         * @return Producto|null
         */
        public function getOrden($id) {
            try {
                return $this->productos[$id];
            } catch (\Throwable $th) {
                return null;
            }
        }
    }

?> 