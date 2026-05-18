<?php

class CourseDAO {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /* ================= GET ALL ================= */
    public function getAll() {
        $sql = "SELECT c.*, cert.name AS cert_name 
                FROM courses c
                JOIN certifications cert ON c.cert_id = cert.id";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ================= ADD ================= */
    public function add($title, $content, $cert_id, $file_path) {

        $stmt = $this->conn->prepare("
            INSERT INTO courses (title, content, cert_id, file_path)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([$title, $content, $cert_id, $file_path]);
    }

    /* ================= DELETE ================= */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM courses WHERE id=?");
        $stmt->execute([$id]);
    }

    /* ================= UPDATE ================= */
    public function update($id, $title, $content, $cert_id, $file_path = null) {

        if ($file_path) {

            $stmt = $this->conn->prepare("
                UPDATE courses 
                SET title=?, content=?, cert_id=?, file_path=?
                WHERE id=?
            ");

            $stmt->execute([$title, $content, $cert_id, $file_path, $id]);

        } else {

            $stmt = $this->conn->prepare("
                UPDATE courses 
                SET title=?, content=?, cert_id=?
                WHERE id=?
            ");

            $stmt->execute([$title, $content, $cert_id, $id]);
        }
    }
    public function getById($id) {

    $stmt = $this->conn->prepare("
        SELECT * FROM courses WHERE id=?
    ");

    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}