<?php
namespace Config;
use mysqli;

class Database{
    private $host= "127.0.0.1";
    private $user = "root";
    private $password = "Vikhyat@9690";
    private $dbname = "blogmanager";

    private $conn;

    public function connect(): mysqli {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);
        if($this->conn->connect_error) {
            die("Connection error: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}