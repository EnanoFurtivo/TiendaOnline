<?php

    class Producto extends Database
    {
        public $id = null;
        public $sku = null;
        public $titulo = null;
        public $descripcion = null;
        public $precio = null;
        public $stock = null;
        public $preview_path = null;

        public $scale = null;
        public $obj_path = null;
        public $mtl_path = null;

        public function __construct($id) {
            $datosProducto = $this->select("SELECT * FROM producto WHERE ID_PRODUCTO = ?", [$id]);
            if (empty($datosProducto))
                return null;

            //Aca traemos mas datos del producto de la base de datos..//
            $this->id = $datosProducto[0]["ID_PRODUCTO"];
            $this->sku = $datosProducto[0]["SKU_PRODUCTO"];
            $this->titulo = $datosProducto[0]["TITULO"];
            $this->precio = $datosProducto[0]["PRECIO"];
            $this->stock = $datosProducto[0]["STOCK_PRODUCTO"];

            $this->scale = $datosProducto[0]["SCALE"];
            $this->preview_path = "/datos/productos/".$this->id."/preview.png";
            $this->obj_path = "/datos/productos/".$this->id."/modelo.obj";
            $this->mtl_path = "/datos/productos/".$this->id."/modelo.mtl";
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
        public static function getProductos($limit = 0) {
            $resultSet = null;

            if ($limit == 0)
                $resultSet = Database::select("SELECT ID_PRODUCTO FROM producto");
            else
                $resultSet = Database::select("SELECT ID_PRODUCTO FROM producto LIMIT ?", [$limit]);

            if(empty($resultSet))
                return null;

            $productos = [];
            foreach ($resultSet as $key => $value) {
                $id = $resultSet[$key]["ID_PRODUCTO"];
                $productos[$id] = new Producto($id);
            }

            return $productos;
        }

        /**
         * Crear nuevo producto.
         * 
         * @param int $sku
         * @param string $titulo
         * @param int $stock
         * @param double $scale
         * @param string $precio
         */
        public static function createProducto($id_vendedor, $sku, $titulo, $stock, $scale, $precio) {
            $resultSet = Database::select("SELECT * FROM producto WHERE SKU_PRODUCTO = ?", [ $sku ]);
            if(!empty($resultSet))
                return false;

            $id = Database::insert("INSERT INTO producto(ID_VENDEDOR, SKU_PRODUCTO, TITULO, STOCK_PRODUCTO, SCALE, PRECIO) VALUES(?,?,?,?,?,?)", [ $id_vendedor, $sku, $titulo, $stock, $scale, $precio ]);
            if(empty($id))
                return false;

            return new Producto($id);
        }

        /**
         * Eliminar un producto.
         * 
         * @param int $sku
         */
        public static function deleteProducto($id) {
            $eliminados = Database::delete("DELETE FROM producto WHERE ID_PRODUCTO = ?", [ $id ]);
            if($eliminados > 0)
                return true;
            else
                return false;
        }

        /**
         * Eliminar logicamente el producto.
         * 
         * @param int $id
         */
        public static function removeProducto($id) {
            Database::update("UPDATE producto SET ACTIVO = ? WHERE ID_PRODUCTO = ?", [false, $id]);
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
            return $this->id;
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