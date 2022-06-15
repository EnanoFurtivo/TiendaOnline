<?php

    class Orden extends Database
    {
        public $id = null;
        public $items = array();
        public $cant_items = null;
        public $estado = null;
        public $fecha = null;

        public $comprador = null;
        public $vendedor = null;

        public function __construct($id) {
            $datosOrden = $this->select("SELECT * FROM orden WHERE ID_ORDEN = ?", [$id]);
            if (empty($datosOrden))
                return null;

            //Aca traemos mas datos del producto de la base de datos..//
            $this->id = $datosOrden[0]["ID_ORDEN"];
            $this->fecha = $datosOrden[0]["FECHA"];
            $this->estado = $datosOrden[0]["ESTADO"];

            $this->comprador = new Comprador($datosOrden[0]["ID_COMPRADOR"]);
            $this->vendedor = new Vendedor($datosOrden[0]["ID_VENDEDOR"]);

            $this->fetchItems();
            $this->cant_items = $this->getCantidadProductos();
        }

        /**
         * Crear nueva orden.
         * 
         * @param string $comprador_id
         * @param string $vendedor_id
         * @param object $items { "id":"cantidad", ... }
         */
        public static function createOrden($comprador_id, $vendedor_id, $items) {
            $id = Database::insert("INSERT INTO orden(ID_COMPRADOR, ID_VENDEDOR) VALUES(?,?)", [ $comprador_id, $vendedor_id ]);

            foreach ($items as $key => $value)
                Database::insert("INSERT INTO orden_item(ID_ORDEN, ID_PRODUCTO, CANTIDAD) VALUES(?,?,?)", [ $id, $key, $items->$key ]);

            return new Orden($id);
        }

        public function fetchItems()
        {
            $resultSet = $this->select("SELECT * FROM orden_item WHERE ID_ORDEN = ?", [$this->id]);
            $items = array();

            foreach ($resultSet as $key => $value) {
                $id = $resultSet[$key]["ID_PRODUCTO"];
                $items[$id] = $resultSet[$key]["CANTIDAD"];
            }

            $this->items = $items;
        }
        
        public function cancelar()
        {
            $this->estado = "cancelada";
            $this->update("UPDATE orden SET ESTADO = ? WHERE ID_ORDEN = ?", [ $this->estado, $this->id ]);
        }

        public function finalizar()
        {
            $this->estado = "finalizada";
            $this->update("UPDATE orden SET ESTADO = ? WHERE ID_ORDEN = ?", [ $this->estado, $this->id ]);
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