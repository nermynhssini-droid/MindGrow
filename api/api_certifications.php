<?php

header("Content-Type: application/json");

require_once "../config/database.php";

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("
    SELECT 
        certifications.id,
        certifications.name,
        certifications.org_id,
        organizations.name AS org_name
    FROM certifications
    LEFT JOIN organizations 
        ON certifications.org_id = organizations.id
");

$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
