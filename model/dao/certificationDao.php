<?php

class CertificationDAO {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /* ================= ADD ================= */
    public function add($name, $org_id) {

        $stmt = $this->conn->prepare("
            INSERT INTO certifications (name, org_id)
            VALUES (?, ?)
        ");

        return $stmt->execute([$name, $org_id]);
    }

    /* ================= UPDATE ================= */
    public function update($id, $name, $org_id) {

        $stmt = $this->conn->prepare("
            UPDATE certifications
            SET name=?, org_id=?
            WHERE id=?
        ");

        return $stmt->execute([$name, $org_id, $id]);
    }

    /* ================= DELETE ================= */
    public function delete($id) {

        $stmt = $this->conn->prepare("
            DELETE FROM certifications WHERE id=?
        ");

        return $stmt->execute([$id]);
    }

    /* ================= GET ALL (utile API JS) ================= */
    public function getAll() {

        $stmt = $this->conn->query("
            SELECT * FROM certifications
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}