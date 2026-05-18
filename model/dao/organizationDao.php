<?php

class OrganizationDAO {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM organizations")
                          ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($name, $image) {
        $sql = "INSERT INTO organizations(name, image) VALUES(?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $image]);
    }

    public function delete($id) {
        $sql = "DELETE FROM organizations WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
    public function getById($id) {

    $sql = "SELECT * FROM organizations WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function update($id, $name, $image) {

    $sql = "
        UPDATE organizations
        SET name = ?, image = ?
        WHERE id = ?
    ";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([$name, $image, $id]);
}
}