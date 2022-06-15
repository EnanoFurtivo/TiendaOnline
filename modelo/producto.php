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

        //             $$$$$$\  $$$$$$$$\ $$$$$$$$\ $$$$$$$$\ $$$$$$$$\ $$$$$$$\   $$$$$$\  
        //            $$  __$$\ $$  _____|\__$$  __|\__$$  __|$$  _____|$$  __$$\ $$  __$$\ 
        //            $$ /  \__|$$ |         $$ |      $$ |   $$ |      $$ |  $$ |$$ /  \__|
        //            $$ |$$$$\ $$$$$\       $$ |      $$ |   $$$$$\    $$$$$$$  |\$$$$$$\  
        //            $$ |\_$$ |$$  __|      $$ |      $$ |   $$  __|   $$  __$$<  \____$$\ 
        //            $$ |  $$ |$$ |         $$ |      $$ |   $$ |      $$ |  $$ |$$\   $$ |
        //            \$$$$$$  |$$$$$$$$\    $$ |      $$ |   $$$$$$$$\ $$ |  $$ |\$$$$$$  |
        //             \______/ \________|   \__|      \__|   \________|\__|  \__| \______/ 
        
        /**
         * Set precio del producto.
         * 
         * @param double precio
         */
        public function setPrecio($precio) {
            $this->precio = $precio;
            Database::update("UPDATE producto SET PRECIO = ? WHERE ID_PRODUCTO = ?", [ $precio, $this->id ]);
        }
        
        /**
         * Set escala del producto.
         * 
         * @param double escala
         */
        public function setEscala($scale) {
            $this->scale = $scale;
            Database::update("UPDATE producto SET SCALE = ? WHERE ID_PRODUCTO = ?", [ $scale, $this->id ]);
        }
        
        /**
         * Set stock del producto.
         * 
         * @param int stock
         */
        public function setStock($stock) {
            $this->stock = $stock;
            Database::update("UPDATE producto SET STOCK_PRODUCTO = ? WHERE ID_PRODUCTO = ?", [ $stock, $this->id ]);
        }
    }

?>