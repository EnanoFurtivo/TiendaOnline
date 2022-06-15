<?php

    class UsuariosController extends ApiController
    {
        /**
         * Parsear la peticion, y ejecutar el metodo deseado
         */
        public function handle_request($uri)
        {
            switch ($uri[0]) 
            {
                case 'generar_token'    : $this->generar_token(); break;
                default                 : $this->enviarRespuesta("No existe el endpoint solicitado.", 404); break;
            }
        }

        /**
         * "/usuarios/generar_token" Endpoint - Generar token para el cliente
         */
        public function generar_token()
        {
            $this->checkRequestMethod('POST');

            $missingUsername = (!isset($_POST["username"]) || $_POST["username"] == "");
            $missingPassword = (!isset($_POST["password"]) || $_POST["password"] == "");
             
            if ($missingUsername || $missingPassword)
            $this->enviarRespuesta("Se esperaban credenciales del cliente validas.", 401);

            try 
            {
                $token = Usuario::generarToken($_POST['username'], $_POST['password']);
                
                if ($token == null)
                    $this->enviarRespuesta('Ocurrio un error al generar el token.', 500);

                $user = Usuario::fetchUsuario($_POST['username']);

                $responseData = json_encode(array('token' => $token, 'user_id' => $user->id));
                $this->enviarRespuesta($responseData, 200);
            } 
            catch (Error $e) 
            {
                $this->enviarRespuesta('Algo salio mal! Por favor contacte al soporte. '.$e->getMessage(), 500);
            }
    
            $this->enviarRespuesta($this->responseData, $this->responseCode);
        }
    }

?>