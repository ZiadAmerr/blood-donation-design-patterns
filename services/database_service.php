<?php
class Database {
    private static $instance = null;
    private $conn;

    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "sdp";

    private function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
?>

