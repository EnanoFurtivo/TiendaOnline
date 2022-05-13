<?php

    class Database
    {
        //credenciales DB//
        private static $sqlServername = "localhost";
        private static $sqlUsername = "tiendaonline";
        private static $sqlPassword = "J0Vh_]J210(AO)Gw";
        private static $dbName = "tienda_online";
        private static $sqlConnection = null;

        /**
         * Obtener conexion con base de datos.
         * 
         * @return mysqli
         */
        private static function getConnection()
        {
            //Si ya existe la conexion devolver//
            if (isset(Database::$sqlConnection) && Database::$sqlConnection != null)
                return Database::$sqlConnection;

            try 
            { 
                $sqlConnection = new mysqli(Database::$sqlServername, 
                                             Database::$sqlUsername, 
                                             Database::$sqlPassword, 
                                             Database::$dbName);

                mysqli_set_charset($sqlConnection, "utf8");

                if ($sqlConnection->connect_error) 
                {
                    echo "Conexion para la base ".Database::$dbName." fallo: ".$sqlConnection->connect_error;
                    return null;
                }
                else
                {
                    Database::$sqlConnection = $sqlConnection;
                    return Database::$sqlConnection;
                }
            } 
            catch (Exception $e) 
            {
                echo 'Error de conexion para la base '.Database::$dbName;
                return null;
            }
        }

        /**
         * Ejecutar query contra base de datos.
         * 
         * @param string $query
         * @param array $params Opcional
         * @param string $dataTypes Opcional
         * @return mysqli_stmt
         */
        private static function executeStatement($query = "", $params = [], $dataTypes = "")
        {
            try 
            {
                $conn = Database::getConnection(Database::$dbName);
                $stmt = $conn->prepare($query);
    
                if ($stmt === false)
                    throw New Exception("No se puede preparar el statement: " . $query);
                
                if ($params) 
                {
                    $hayDataTypes = ($dataTypes != "") ? true : false;
                    foreach ($params as $key => $value)
                    {
                        if (!$hayDataTypes)
                            $dataTypes .= "s";

                        if (strpos($dataTypes, $key) == "s")
                            $params[$key] = $conn->real_escape_string($params[$key]);
                    }

                    $stmt->bind_param($dataTypes, ...$params);
                }
    
                $stmt->execute();
    
                return $stmt;
            } 
            catch(Exception $e) 
            {
                throw New Exception($e->getMessage());
            }   
        }

        /**
         * Ejecutar select contra base de datos.
         * 
         * @param string $query
         * @param array $params Opcional
         * @param string $dataTypes Opcional
         * @return array
         */
        protected static function select($query = "", $params = [], $dataTypes = "")
        {
            try 
            {
                $stmt = Database::executeStatement($query, $params, $dataTypes);
                $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);               
                $stmt->close();
                return $result;
            } 
            catch(Exception $e) 
            {
                throw New Exception($e->getMessage());
            }

            return null;
        }

        /**
         * Ejecutar update contra base de datos.
         * 
         * @param string $query
         * @param array $params Opcional
         * @param string $dataTypes Opcional
         * @return bool
         */
        public static function update($query = "", $params = [], $dataTypes = "")
        {
            try 
            {
                $stmt = Database::executeStatement($query, $params, $dataTypes);
                $result = $stmt->get_result();     
                $stmt->close();
                return $result;
            } 
            catch(Exception $e) 
            {
                throw New Exception($e->getMessage());
            }

            return null;
        }

        /**
         * Ejecutar insert contra base de datos.
         * 
         * @param string $query
         * @param array $params Opcional
         * @param string $dataTypes Opcional
         * @return bool
         */
        public static function insert($query = "", $params = [], $dataTypes = "")
        {
            try 
            {
                $stmt = Database::executeStatement($query, $params, $dataTypes);
                $result = $stmt->insert_id;              
                $stmt->close();
                return $result;
            } 
            catch(Exception $e) 
            {
                throw New Exception($e->getMessage());
            }

            return null;
        }
    }

?>