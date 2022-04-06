<?php

    class Database
    {
        //credenciales DB//
        private static $sqlServername = "localhost";
        private static $sqlUsername = "tiendaonline";
        private static $sqlPassword = "J0Vh_]J210(AO)Gw";
        private static $dbPrefix = "to_";

        //Array de conexiones//
        private $sqlConnections = array();

        //Obtener una nueva conexion si no existe//
        private function getConnection($dbName)
        {
            $dbName = Database::$dbPrefix.$dbName;

            //Si ya existe la conexion devolver//
            if (isset($this->sqlConnections[$dbName]) && $this->sqlConnections[$dbName] != null)
                return $this->sqlConnections[$dbName];

            try 
            { 
                $sqlConnection = new mysqli(Database::$sqlServername, 
                                             Database::$sqlUsername, 
                                             Database::$sqlPassword, 
                                             $dbName);

                mysqli_set_charset($sqlConnection, "utf8");

                if ($sqlConnection->connect_error) 
                {
                    echo "Conexion para la base ".$dbName." fallo: ".$sqlConnection->connect_error;
                    return null;
                }
                else
                {
                    $this->sqlConnections[$dbName] = $sqlConnection;
                    return $this->sqlConnections[$dbName];
                }
            } 
            catch (Exception $e) 
            {
                echo 'SQL error de conexion para la base '.$dbName;
                return null;
            }
        }

        private function executeStatement($dbName = "", $query = "" , $types, $params = [])
        {
            try {
                $conn = $this->getConnection($dbName);
                $stmt = $conn->prepare($query);
    
                if($stmt === false) {
                    throw New Exception("Unable to do prepared statement: " . $query);
                }
    
                if( $params && $types ) {
                    $stmt->bind_param($types, ...$params);
                }
    
                $stmt->execute();
    
                return $stmt;
            } catch(Exception $e) {
                throw New Exception( $e->getMessage() );
            }   
        }

        protected function select($dbName = "", $query = "", $types, $params = [])
        {
            try {
                $stmt = $this->executeStatement($dbName, $query, $types, $params);
                $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);               
                $stmt->close();
    
                return $result;
            } catch(Exception $e) {
                throw New Exception( $e->getMessage() );
            }
            return false;
        }
    
    }

?>