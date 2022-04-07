<?php

    class Producto extends Database
    {
        /**
         * Obtener listado de productos.
         * 
         * @param int $limit
         * @return array
         */
        public function getProductos($limit)
        {
            return $this->select("SELECT * FROM producto LIMIT ?", [$limit]);
        }
    }

?>