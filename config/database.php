<?php

define("BASE_URL", "/mindgrow/");

class Database {

    private $host = "localhost";
    private $db_name = "mindgrow";
    private $username = "root";
    private $password = "";

    public function getConnection() {

        $conn = null;

        try {
            $conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );

            // 🔥 IMPORTANT: activer les erreurs PDO
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            die("Connection error: " . $e->getMessage());
        }

        return $conn;
    }
}