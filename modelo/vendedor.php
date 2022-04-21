<?php

    class Vendedor extends Usuario
    {
        private $productos = null;

        public function __construct($username) {
            parent::__construct($username);
            $this->username = $username;
            $this->fetchProductos();
        }
         
        /** 
         * Actualizar los productos de un vendedor.
         * 
         * @param string $token
         * @return bool 
         */
        public function fetchProductos() {
            $resultSet = $this->select("SELECT * FROM producto WHERE ID_USUARIO = ?", [$this->idUsuario]);
            $productos = [];

            foreach ($resultSet as $key => $value) {
                $idProducto = $resultSet[$key]["ID_PRODUCTO"];
                $productos[$idProducto] = new Producto($idProducto);
            }

            $this->$productos = $productos;
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
         * @param int $idProducto
         * @return Producto|null
         */
        public function getOrden($idProducto) {
            try {
                return $this->productos[$idProducto];
            } catch (\Throwable $th) {
                return null;
            }
        }
    }

?> 