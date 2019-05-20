<?php

ini_set( "soap.wsdl_cache_enabled", 0 );

include_once 'Logger.php';

// mi server
class SoapServerEx {
  
  /**
   * @var mysqli $conn
   */
  private $conn = NULL;

  /**
   * @var string $req
   */
  private $req = '';

  const LOG_FILE = 'soap.log';

  function __construct() {
    $cf = parse_ini_file('config.ini');
    $this->conn = new mysqli($cf["host"], $cf["user"], $cf["pass"], $cf["db"]);

    // Logger INFO
    $xml = simplexml_load_file('php://input');
    $elements = $xml->children('soapenv', true)->Body->children('v1', true);
    $msg = 'Request ';
    foreach ($elements as $key => $params) {
      $msg .= $key;
      $this->req = $key;
      foreach ($params as $attr => $value) {
        $msg .= " | " . $attr . ": " . $value;
      }
    }
    Logger::log('INFO', $msg, self::LOG_FILE);

    // Check connection
    if ($this->conn->connect_error) {
      $this->_error("Connection failed: " . $this->conn->connect_error);
    }
  }

  /**
   * Añadir un usuario
   * @access public
   * @var string $username
   * @var string $password
   * @var string $email
   * @return string
   */
  public function addUser($username, $password, $email) {
    $username = $this->conn->escape_string($username);
    $password = $this->conn->escape_string($password);
    $email = $this->conn->escape_string($email);
    if ($this->conn->query("INSERT INTO users (username, password, email) VALUES ('$username', SHA1('$password'), '$email');") === TRUE) {
      return "success";
    }

    if ($this->conn->error) {
      $this->_debug($this->conn->error);
    }

    $this->_error();
  }

  /**
   * activar usuario
   * @access public
   * @var string $username
   * @return string
   */
  public function activateUser($username) {
    return $this->_toggleUser($username, 1);
  }

  /**
   * Desactivar usuario
   * @access public
   * @var string $username
   * @return string
   */
  public function deactivateUser($username) {
    return $this->_toggleUser($username, 0);
  }

  /**
   * Cambiar estado de usuario
   * @access private
   * @var string $username
   * @var int $enabled
   * @return string
   */
  private function _toggleUser($username, $enabled) {
    $username = $this->conn->escape_string($username);
    if ($this->conn->query("UPDATE users SET enabled = $enabled WHERE username = '$username';") === TRUE) {
      return "OK!";
    }
    if ($this->conn->error) {
      $this->_debug($this->conn->error);
    }
    $this->_error();
  }

  /**
   * Obtener usuario
   * @access public
   * @var string $username
   * @return array
   */
  public function getUser($username) { 
    $username = $this->conn->escape_string($username);
    $result = $this->conn->query("SELECT * FROM users WHERE username = '$username' AND enabled = 1 LIMIT 1;"); 
    if ( $result && $result->num_rows > 0 ) {
        while ($row = $result->fetch_assoc()) {
            return [
              'username' => $row['username'],
              'password' => $row['password'],
              'email' => $row['email']
            ];
        }
    }

    if ($this->conn->error) {
      $this->_debug($this->conn->error);
    }
    
    $this->_error();
  }

  /**
   * Log error
   * @access private
   */
  private function _error($msg = '') {
    if (empty($msg)) {
      $msg = "Hubo un error al intentar procesar request {$this->req}";
    }
    Logger::log('ERROR', $msg, self::LOG_FILE);
    die($msg);
  }

  /**
   * Log Debug
   * @access private
   * @var string $msg
   */
  private function _debug($msg) {
    Logger::log('DEBUG', $msg, self::LOG_FILE);
  }

  public function __destruct() {
    if ($this->conn) {
      $this->conn->close();
    }
  }
}

$server = new SoapServer('user.wsdl', ['uri'=>'http://localhost/soap_server_ex/service.php']);
$server->setClass('SoapServerEx');
$server->handle();