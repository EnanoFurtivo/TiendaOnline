<?php

    class Producto extends Database
    {
        private $idProducto = null;
        private $sku = null;
        private $titulo = null;
        private $descripcion = null;
        private $precio = null;
        private $stock = null;

        public function __construct($idProducto) {
            $datosProducto = $this->select("SELECT * FROM producto WHERE ID_PRODUCTO = ?", [$idProducto]);
            if (empty($datosProducto))
                return null;

            //Aca traemos mas datos del producto de la base de datos..//
            $this->idProducto = $datosProducto[0]["ID_PRODUCTO"];
            $this->sku = $datosProducto[0]["SKU"];
            $this->titulo = $datosProducto[0]["TITULO"];
            $this->descripcion = $datosProducto[0]["DESCRIPCION"];
            $this->precio = $datosProducto[0]["PRECIO"];
            $this->stock = $datosProducto[0]["STOCK"];
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
         * Obtener listado de productos.
         * 
         * @param int $limit
         * @return array
         */
        public static function getProductos($limit) {
            return Database::select("SELECT * FROM producto LIMIT ?", [$limit]);
        }

        /**
         * Crear nuevo producto.
         * 
         * @param int $sku
         * @param string $titulo
         * @param string $descripcion
         * @param double $costo
         * @param int $stock
         */
        public static function createProducto($sku, $titulo, $descripcion, $costo, $stock) {
            $resultSet = Database::select("SELECT * FROM producto WHERE SKU = ?", [$sku]);

            if(!empty($resultSet))
                return false;

            $idProducto = Database::insert("INSERT INTO producto('TITULO','DESCRIPCION','COSTO','STOCK') VALUES(?,?,?,?,?)", [ $titulo, $descripcion, $costo, $stock]);

            if(empty($idProducto))
                return false;

            return new Producto($idProducto);
        }

        /**
         * Eliminar logicamente el producto.
         * 
         * @param int $idProducto
         */
        public static function removeProducto($idProducto) {
            Database::update("UPDATE producto SET ACTIVO = ? WHERE ID_PRODUCTO = ?", [false, $idProducto]);
        }

        /**
         * Obtener los assets para el cliente de unity.
         * 
         * @param int $idProducto
         * @return blob
         */
        public static function get3DAssets($idProducto) {
            throw new Exception(); //no implementado aun
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
         * Obtener id del producto.
         * 
         * @return int id
         */
        public function getId() {
            return $this->idProducto;
        }

        /**
         * Obtener titulo del producto.
         * 
         * @return string titulo
         */
        public function getTitulo() {
            return $this->titulo;
        }

        /**
         * Obtener descripcion del producto.
         * 
         * @return string descripcion
         */
        public function getDescripcion() {
            return $this->descripcion;
        }

        /**
         * Obtener precio del producto.
         * 
         * @return double precio
         */
        public function getPrecio() {
            return $this->precio;
        }

        /**
         * Obtener stock del producto.
         * 
         * @return int stock
         */
        public function getStock() {
            return $this->stock;
        }
        
    }

?>