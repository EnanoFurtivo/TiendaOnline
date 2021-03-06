<?php

    class Usuario extends Database
    {
        public $id = null;
        public $username = null;
        protected $ordenes = array();
        public $mail = null;
        public $telefono = null;
        public $preview_path = null;

        public function __construct($id) {
            $datosUsuario = $this->select("SELECT * FROM usuario WHERE ID_USUARIO = ?", [$id]);
            if (empty($datosUsuario))
                return null;

            //Aca traemos mas datos del usuario de la base de datos..//
            $this->id = $datosUsuario[0]["ID_USUARIO"];
            $this->username = $datosUsuario[0]["USERNAME"];
            $this->mail = $datosUsuario[0]["MAIL"];
            $this->telefono = $datosUsuario[0]["TELEFONO"];
            $this->preview_path = "/datos/usuarios/".$this->id."/preview.png";
        }
        
        /**
         * Obtener usuario dado su nombre de usuario.
         * 
         * @param string $username
         * @return Usuario
         */
        public static function fetchUsuario($username)
        {
            $datosUsuario = Database::select("SELECT ID_USUARIO FROM usuario WHERE USERNAME = ?", [$username]);
            if (empty($datosUsuario))
                return null;
            $id = $datosUsuario[0]["ID_USUARIO"];
            $user = new Usuario($id);
            return $user;
        }

        /**
         * Actualizar ordenes del usuario.
         * @return void
         */
        protected function fetchOrdenes() {;}

        //                     $$$$$$\ $$$$$$$$\  $$$$$$\ $$$$$$$$\ $$$$$$\  $$$$$$\  
        //                    $$  __$$\\__$$  __|$$  __$$\\__$$  __|\_$$  _|$$  __$$\ 
        //                    $$ /  \__|  $$ |   $$ /  $$ |  $$ |     $$ |  $$ /  \__|
        //                    \$$$$$$\    $$ |   $$$$$$$$ |  $$ |     $$ |  $$ |      
        //                     \____$$\   $$ |   $$  __$$ |  $$ |     $$ |  $$ |      
        //                    $$\   $$ |  $$ |   $$ |  $$ |  $$ |     $$ |  $$ |  $$\ 
        //                    \$$$$$$  |  $$ |   $$ |  $$ |  $$ |   $$$$$$\ \$$$$$$  |
        //                     \______/   \__|   \__|  \__|  \__|   \______| \______/                                                 

        /** 
         * Validar las credenciales de un usuario.
         * 
         * @param string $username
         * @param string $password sha256 encoded
         * @return bool 
         */
        public static function validarCredenciales($username, $password) {
            $resultSet = Database::select("SELECT PASSWORD FROM usuario WHERE USERNAME = ?", [$username]);

            if(empty($resultSet))
                return false;
            $storedPassword = $resultSet[0]["PASSWORD"];

            if (Encryption::decrypt($storedPassword) == $password)
                return true;
            else
                return false;
        }

        /** 
         * Crear un nuevo usuario.
         * 
         * @param string $username
         * @param string $password sha256 encoded
         * @param string $mail
         * @param string $telefono
         * @param string $tipoCuenta Vendedor | Comprador
         * @return Comprador|Vendedor|null 
         */
        public static function createUsuario($username, $password, $mail, $telefono, $tipoCuenta, $temp_name = null, $name = null) {
            $resultSet = Database::select("SELECT * FROM usuario WHERE USERNAME = ?", [$username]);

            if(!empty($resultSet))
                return null;
            
            $passwordHash = hash('sha256', $password);
            $passwordEncrypted = Encryption::encrypt($passwordHash);
            $id = Database::insert("INSERT INTO usuario(USERNAME, PASSWORD, MAIL, TELEFONO, TIPO_USUARIO) VALUES(?,?,?,?,?)", [$username, $passwordEncrypted, $mail, $telefono, $tipoCuenta]);
            
            if(empty($id))
                return null;

            if ($tipoCuenta == 0)                   //Comprador
                $resultObj = new Comprador($id);
            else                                    //Vendedor
                $resultObj = new Vendedor($id);
                
            $dir = "datos/usuarios/".$id."/";
            mkdir($dir, 0700, true);

            if ($temp_name == null)
                copy("assets/preview-user.png", $dir."preview.png");
            else
            {
                $imageFileType = strtolower(pathinfo($dir.basename($name), PATHINFO_EXTENSION));
                move_uploaded_file($temp_name, $dir."preview.".$imageFileType);
            }

            return $resultObj;
        }

        /**
         * Eliminar logicamente el usuario.
         * 
         * @param int $id
         */
        public static function removeUsuario($id) {
            Database::update("UPDATE usuario SET ACTIVO = ? WHERE ID_USUARIO = ?", [false, $id]);
        }

        /** 
         * Validar el token de un usuario.
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
         * Generar un token de acceso para el usuario.
         * 
         * @param string $username
         * @param string $password
         * @return string|null
         */
        public static function generarToken($username, $password) {
            if (!Usuario::validarCredenciales($username, $password))
                return null;

            $id = Database::select("SELECT ID_USUARIO FROM usuario WHERE USERNAME = ?",[$username])[0]["ID_USUARIO"];
            if(empty($id))
                return null;

            $usuario = new Usuario($id);
            if($usuario == null)
                return null;

            $newToken = bin2hex(random_bytes(64));
            Database::insert("INSERT INTO token(ID_USUARIO, AUTHTOKEN) VALUES (?,?)", [$usuario->getId(),$newToken]);
            return $newToken;
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
         * Obtener id del usuario.
         * 
         * @return int id
         */
        public function getId() {
            return $this->id;
        }

        /**
         * Obtener nombre del usuario.
         * 
         * @return string username
         */
        public function getUsername() {
            return $this->username;
        }

        /**
         * Obtener mail del usuario.
         * 
         * @return string mail
         */
        public function getMail() {
            return $this->mail;
        }

        /**
         * Obtener telefono del usuario.
         * 
         * @return string telefono
         */
        public function getTelefono() {
            return $this->telefono;
        }

        /**
         * Obtener las ordenes del usuario.
         * 
         * @return array<Orden>|null
         */
        public function getOrdenes() {
            $this->fetchOrdenes();
            return $this->ordenes;
        }

        /**
         * Obtener una orden.
         * 
         * @param int $idOrden
         * @return Orden|null
         */
        public function getOrden($idOrden) {
            try {
                return $this->ordenes[$idOrden];
            } catch (\Throwable $th) {
                return null;
            }
        }

    }

?>