<?php

    class Producto extends Database
    {
        public function getProductos($limit)
        {
            return $this->select("productos", "SELECT * FROM producto LIMIT ?", "i", [$limit]);
        }
    }

?>