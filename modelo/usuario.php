<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

    class Usuario extends Database
    {
        private $idUsuario = null;
        private $username = null;

        public function __construct($username) {
            $this->username = $username;
        }

        /**
         * Obtiene una instancia de usuario.
         * 
         * @param string $username
         * @return User
         */
        public static function getUsuario($username) {
            $usuario = new static($username);
            return $usuario;
        }

        //                    $$$$$$\ $$$$$$$$\  $$$$$$\ $$$$$$$$\ $$$$$$\  $$$$$$\  
        //                    $$  __$$\\__$$  __|$$  __$$\\__$$  __|\_$$  _|$$  __$$\ 
        //                    $$ /  \__|  $$ |   $$ /  $$ |  $$ |     $$ |  $$ /  \__|
        //                    \$$$$$$\    $$ |   $$$$$$$$ |  $$ |     $$ |  $$ |      
        //                     \____$$\   $$ |   $$  __$$ |  $$ |     $$ |  $$ |      
        //                    $$\   $$ |  $$ |   $$ |  $$ |  $$ |     $$ |  $$ |  $$\ 
        //                    \$$$$$$  |  $$ |   $$ |  $$ |  $$ |   $$$$$$\ \$$$$$$  |
        //                     \______/   \__|   \__|  \__|  \__|   \______| \______/                                                 

        /** 
         * Valida las credenciales de un usuario.
         * 
         * @param string $username
         * @param string $password sha256 encoded
         * @return bool 
         */
        public static function validarCredenciales($username, $password) {
            $encryption = new Encryption();
            $resultSet = Database::select("SELECT PASSWORD FROM usuario WHERE USERNAME = ?", [$username]);

            if(empty($resultSet))
                return false;

            $storedPassword = $resultSet[0]["PASSWORD"];

            if ($encryption->decrypt($storedPassword) == $password)
                return true;
            else
                return false;
        }
        /** 
         * Valida el token de un usuario.
         * 
         * @param string $token
         * @return bool 
         */
        public static function validarToken($token) {
            $tokensUsuario = Database::select("SELECT * FROM token WHERE AUTHTOKEN = ? AND DATE_ADD(TIMESTAMP_EMITIDO, INTERVAL DIAS_VALIDO DAY) > ?", [$token,time()]);
            
            if (!empty($tokensUsuario))
                return true;
            else
                return false;
        }
        /** 
         * Genera un token de acceso para el usuario.
         * 
         * @param string $username
         * @param string $password
         * @return string|null
         */
        public static function generarToken($username, $password) {
            if (!Usuario::validarCredenciales($username, $password))
                return null;

            $usuario = Usuario::getUsuario($username);
            if (!$usuario->fetchDatosUsuario())
                return null;

            $newToken = bin2hex(random_bytes(64));
            Database::insert("INSERT INTO token(ID_USUARIO, AUTHTOKEN) VALUES (?,?)", [$usuario->getId(),$newToken]);
            return $newToken;
        }
        /** 
         * Obtener datos del usuario.
         * 
         * @param string $username
         * @return bool operacion exitosa
         */
        public function fetchDatosUsuario() {
            $datosUsuario = $this->select("SELECT * FROM usuario WHERE USERNAME = ?", [$this->username]);

            if (empty($datosUsuario))
                return false;

            //Aca traemos mas datos del usuario de la base de datos..//
            $this->idUsuario = $datosUsuario[0]["ID_USUARIO"];
            
            return true;
        }

        //            $$$$$$\  $$$$$$$$\ $$$$$$$$\ $$$$$$$$\ $$$$$$$$\ $$$$$$$\   $$$$$$\  
        //            $$  __$$\ $$  _____|\__$$  __|\__$$  __|$$  _____|$$  __$$\ $$  __$$\ 
        //            $$ /  \__|$$ |         $$ |      $$ |   $$ |      $$ |  $$ |$$ /  \__|
        //            $$ |$$$$\ $$$$$\       $$ |      $$ |   $$$$$\    $$$$$$$  |\$$$$$$\  
        //            $$ |\_$$ |$$  __|      $$ |      $$ |   $$  __|   $$  __$$<  \____$$\ 
        //            $$ |  $$ |$$ |         $$ |      $$ |   $$ |      $$ |  $$ |$$\   $$ |
        //            \$$$$$$  |$$$$$$$$\    $$ |      $$ |   $$$$$$$$\ $$ |  $$ |\$$$$$$  |
        //             \______/ \________|   \__|      \__|   \________|\__|  \__| \______/ 

        /**
         * Obtener nombre del usuario.
         * 
         * @return string username
         */
        public function getUsername() {
            return $this->username;
        }
        /**
         * Obtener id del usuario.
         * 
         * @return string id
         */
        public function getId() {
            return $this->idUsuario;
        }
        /**
         * Obtener listado de ordenes para un usuario determinado.
         * 
         * @param int $idUsuario
         * @return array
         */
        public function getOrdenes($idUsuario) {
            return $this->select("SELECT * FROM orden WHERE ID_USUARIO = ?", [$idUsuario]);
        }
        /**
         * Obtener una orden.
         * 
         * @param int $idUsuario
         * @return array
         */
        public function getOrden($idOrden) {
            return $this->select("SELECT * FROM orden WHERE ID_ORDEN = ?", [$idOrden]);
        }

        //            $$$$$$\  $$$$$$$$\ $$$$$$$$\ $$$$$$$$\ $$$$$$$$\ $$$$$$$\   $$$$$$\  
        //            $$  __$$\ $$  _____|\__$$  __|\__$$  __|$$  _____|$$  __$$\ $$  __$$\ 
        //            $$ /  \__|$$ |         $$ |      $$ |   $$ |      $$ |  $$ |$$ /  \__|
        //            \$$$$$$\  $$$$$\       $$ |      $$ |   $$$$$\    $$$$$$$  |\$$$$$$\  
        //            $$\   $$ |$$ |         $$ |      $$ |   $$ |      $$ |  $$ |$$\   $$ |
        //            \$$$$$$  |$$$$$$$$\    $$ |      $$ |   $$$$$$$$\ $$ |  $$ |\$$$$$$  |
        //             \______/ \________|   \__|      \__|   \________|\__|  \__| \______/ 

    }

?>