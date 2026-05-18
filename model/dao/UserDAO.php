<?php
class UserDAO {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getByEmail($email) {

        $stmt = $this->conn->prepare("
            SELECT * FROM users WHERE email = ?
        ");

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $email, $password) {

        $stmt = $this->conn->prepare("
            INSERT INTO users(name,email,password,role)
            VALUES(?,?,?,?)
        ");

        return $stmt->execute([$name, $email, $password, 'user']);
    }

    public function updateToken($id, $token) {

        $stmt = $this->conn->prepare("
            UPDATE users SET remember_token=? WHERE id=?
        ");

        $stmt->execute([$token, $id]);
    }

    public function clearToken($id) {

        $stmt = $this->conn->prepare("
            UPDATE users SET remember_token=NULL WHERE id=?
        ");

        $stmt->execute([$id]);
    }
    public function removeRememberToken($id) {

    $stmt = $this->conn->prepare("
        UPDATE users
        SET remember_token = NULL
        WHERE id = ?
    ");

    return $stmt->execute([$id]);
}
}