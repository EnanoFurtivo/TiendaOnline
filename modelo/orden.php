<?php

    class Orden extends Database
    {
        private $idOrden = null;
        private $items = null;
        private $estado = null;
        private $fecha = null;

        private $comprador = null;
        private $vendedor = null;

        public function __construct($idOrden) {
            $datosOrden = $this->select("SELECT * FROM orden WHERE ID_ORDEN = ?", [$idOrden]);
            if (empty($datosOrden))
                return null;

            //Aca traemos mas datos del producto de la base de datos..//
            $this->idOrden = $datosOrden[0]["ID_PRODUCTO"];
            $this->fecha = $datosOrden[0]["FECHA"];
            $this->estado = 0;

            $usernameComprador = $this->select("SELECT USERNAME FROM usuario WHERE ID_USUARIO = ?", [$datosOrden[0]["ID_COMPRADOR"]]);
            $this->comprador = new Comprador($usernameComprador);

            $usernameVendedor = $this->select("SELECT USERNAME FROM usuario WHERE ID_USUARIO = ?", [$datosOrden[0]["ID_VENDEDOR"]]);
            $this->vendedor = new Vendedor($usernameVendedor);

            $this->fetchItems();
        }

        /**
         * Crear nueva orden.
         * 
         * @param Comprador $comprador
         * @param Vendedor $vendedor
         * @param array $items [ idProducto => cantidad, ... ]
         */
        public static function createOrden($comprador, $vendedor, $items) {
            $idOrden = Database::insert("INSERT INTO orden('ID_COMPRADOR','ID_VENDEDOR') VALUES(?,?)", [ $comprador->getId(), $vendedor->getId() ]);

            foreach ($items as $key => $value)
                Database::insert("INSERT INTO orden_item('ID_ORDEN','ID_PRODUCTO','CANTIDAD') VALUES(?,?,?)", [ $idOrden, $key, $items[$key]]);

            return new Orden($idOrden);
        }

        public function fetchItems()
        {
            $resultSet = $this->select("SELECT * FROM orden_item WHERE ID_ORDEN = ?", [$this->idOrden]);
            $items = [];

            foreach ($resultSet as $key => $value) {
                $idProducto = $resultSet[$key]["ID_PRODUCTO"];
                $items[$idProducto] = $resultSet[$key]["CANTIDAD"];
            }

            $this->$items = $items;
        }
        
        public function cancelar()
        {
            $this->update("UPDATE orden SET ESTADO = ? WHERE ID_PRODUCTO = ?", [1, $this->idOrden]);
            $this->estado = 1;
        }

        public function finalizar()
        {
            $this->update("UPDATE orden SET ACTIVO = ? WHERE ID_PRODUCTO = ?", [2, $this->idOrden]);
            $this->estado = 2;
        }

        public function getCantidadProductos()
        {
            $sum = 0;
            foreach ($this->items as $key => $value)
                $sum += $this->items[$key];
            return $sum;
        }

    }

?>