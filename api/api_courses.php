<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->query("
    SELECT courses.*, certifications.name AS cert_name
    FROM courses
    JOIN certifications ON courses.cert_id = certifications.id
");

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));