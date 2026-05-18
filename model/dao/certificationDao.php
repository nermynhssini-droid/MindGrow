<?php

class CertificationDAO {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($name, $org_id) {

        $stmt = $this->conn->prepare("
            INSERT INTO certifications (name, org_id)
            VALUES (?, ?)
        ");

        return $stmt->execute([$name, $org_id]);
    }

    public function update($id, $name, $org_id) {

        $stmt = $this->conn->prepare("
            UPDATE certifications
            SET name=?, org_id=?
            WHERE id=?
        ");

        return $stmt->execute([$name, $org_id, $id]);
    }

    public function delete($id) {

        $stmt = $this->conn->prepare("
            DELETE FROM certifications WHERE id=?
        ");

        return $stmt->execute([$id]);
    }

    public function getAll() {

        $stmt = $this->conn->query("
            SELECT * FROM certifications
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}