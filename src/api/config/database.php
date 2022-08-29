<?php

class Database {

    // учетные данные базы данных
    private $host = "mysql_db";
    private $db_name = "employee_db";
    private $username = "root";
    private $password = "root";
    public $conn;

    // получаем соединение с базой данных
    public function getConnection() {

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>