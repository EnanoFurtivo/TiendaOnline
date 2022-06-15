<?php

    class Comprador extends Usuario
    {
        public function __construct($id) {
            parent::__construct($id);
        }

        /**
         * Actualizar ordenes del comprador.
         * @return void
         */
        protected function fetchOrdenes() {
            $resultSet = $this->select("SELECT * FROM orden WHERE ID_COMPRADOR = ?", [$this->id]);
            if(empty($resultSet))
                return;
            
            $ordenes = array();
            foreach ($resultSet as $key => $value) {
                $idOrden = $resultSet[$key]["ID_ORDEN"];
                $ordenes[$idOrden] = new Orden($idOrden);
            }

            $this->ordenes = $ordenes;
        }
    }

?>