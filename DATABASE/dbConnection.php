<?php

class Database {
    private $host = "localhost";
    private $db_name = "jobconnect";  // Change this to your database name
    private $username = "root";       // Change this to your database username
    private $password = "";           // Change this to your database password
    public $conn;

    // Method to establish and return a database connection
    public function getConnect() {
        $this->conn = null;

        try {
            // Attempting to connect using PDO
            $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name, $this->username, $this->password);

            // Setting PDO error mode to exception for better error handling
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->conn;  // Returning the PDO connection object

        } catch (PDOException $exception) {
            // Handling connection errors
            echo "Connection error: " . $exception->getMessage();
            return null;  // Return null if connection fails
        }
    }
}
?>