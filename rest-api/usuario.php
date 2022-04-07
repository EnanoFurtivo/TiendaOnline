<?php

    class UsuarioController extends ApiController
    {
        /**
         * "/usuario/generarToken" Endpoint - Generar token para el cliente
         */
        public function generar_token()
        {
            $this->checkRequestMethod('POST');

            try 
            {
                $missingUsername = (!isset($_POST["username"]) || $_POST["username"] == "");
                $missingPassword = (!isset($_POST["password"]) || $_POST["password"] == "");

                if ($missingUsername || $missingPassword)
                    $this->enviarRespuesta("Se esperaban credenciales del cliente validas.", 401);

                $token = Usuario::generarToken($_POST['username'], $_POST['password']);
                
                if ($token == null)
                    $this->enviarRespuesta('Ocurrio un error al generar el token.', 500);

                $responseData = json_encode(array('token' => $token));
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