<?php

    define("DB_HOST", "localhost");
    define("DB_USERNAME", "tiendaonline");
    define("DB_PASSWORD", "J0Vh_]J210(AO)Gw");
    define("DB_DATABASE_NAME", "tiendaonline");
    
    define("PROJECT_ROOT_PATH", __DIR__ . "/../");

    class Database
    {
        protected $connection = null;
    
        public function __construct()
        {
            try {
                $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
            
                if ( mysqli_connect_errno()) {
                    throw new Exception("Could not connect to database.");   
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage());   
            }           
        }
    
        public function select($query = "", $types, $params = [])
        {
            try {
                $stmt = $this->executeStatement( $query, $types, $params );
                $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);               
                $stmt->close();
    
                return $result;
            } catch(Exception $e) {
                throw New Exception( $e->getMessage() );
            }
            return false;
        }
    
        private function executeStatement($query = "" , $types, $params = [])
        {
            try {
                $stmt = $this->connection->prepare( $query );
    
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
    }

    class Producto extends Database
    {
        public function getProductos($limit)
        {
            return $this->select("SELECT * FROM producto LIMIT ?", "i", [$limit]);
        }
    }

    class BaseController
    {
        /**
         * __call magic method.
         */
        public function __call($name, $arguments)
        {
            $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
        }
    
        /**
         * Get URI elements.
         * 
         * @return array
         */
        protected function getUriSegments()
        {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $uri = explode( '/', $uri );
    
            return $uri;
        }
    
        /**
         * Get querystring params.
         * 
         * @return array
         */
        protected function getQueryStringParams()
        {
            return parse_str($_SERVER['QUERY_STRING'], $query);
        }
    
        /**
         * Send API output.
         *
         * @param mixed  $data
         * @param string $httpHeader
         */
        protected function sendOutput($data, $httpHeaders=array())
        {
            header_remove('Set-Cookie');
    
            if (is_array($httpHeaders) && count($httpHeaders)) {
                foreach ($httpHeaders as $httpHeader) {
                    header($httpHeader);
                }
            }
    
            echo $data;
            exit;
        }
    }

    class UserController extends BaseController
    {
        /**
         * "/user/list" Endpoint - Get list of users
         */
        public function listAction()
        {
            $strErrorDesc = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $arrQueryStringParams = $this->getQueryStringParams();
     
            if (strtoupper($requestMethod) == 'GET') {
                try {
                    $userModel = new Producto();
     
                    $intLimit = 10;
                    if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                        $intLimit = $arrQueryStringParams['limit'];
                    }
     
                    $arrUsers = $userModel->getProductos($intLimit);
                    $responseData = json_encode($arrUsers);
                } catch (Error $e) {
                    $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
     
            // send output
            if (!$strErrorDesc) {
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            } else {
                $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                    array('Content-Type: application/json', $strErrorHeader)
                );
            }
        }
    }

    require __DIR__ . "/inc/bootstrap.php";
    
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );
    
    if ((isset($uri[2]) && $uri[2] != 'user') || !isset($uri[3])) {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
    
    require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
    
    $objFeedController = new UserController();
    $strMethodName = $uri[3] . 'Action';
    $objFeedController->{$strMethodName}();
?>