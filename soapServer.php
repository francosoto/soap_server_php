<?php
$config = parse_ini_file('config.ini');

ini_set( "soap.wsdl_cache_enabled", 0 );
// server
class SoapServerEx {
  // public function getMessage() {
  //   return 'Hello,World!';
  // }

  private function _connectDB() {
    $cf = $config["db_connection"];
    $this->conn = new mysqli($cf["host"], $cf["user"], $cf["pass"], $cf["db"]);

    // Check connection
    if ($this->conn->connect_error) {
        die("Connection failed: " . $this->conn->connect_error);
    }
  }


  public function addUser($username, $password, $email) {
    if ($this->conn->query("INSERT INTO user VALUES ($username, $password, $email, 1);") === TRUE) {
      return TRUE;
    } else {
      return "error";
    }
  }
  public function activateUser($username) {
    return $this->conn->query("UPDATE user SET enabled = 1 WHERE username = {$username};");
  }
  public function deactivateUser($username) {
    return $this->conn->query("UPDATE user SET enabled = 0 WHERE username = {$username};");
  }

  public function getUser($username) { 
    $result = $this->conn->query("SELECT * FROM user WHERE username = {$username} LIMIT 1;"); 
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row;
        }
    }
    return [];
  }

  public function __close() {
    $this->conn->close();
  }
}

$options = array('uri'=>'http://localhost/user');
$server = new SoapServer('user.wsdl', $options);
$server->setClass('SoapServerEx');
$server->handle();