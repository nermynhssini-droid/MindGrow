<?php

header("Content-Type: application/json");

require_once "../config/database.php";

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->query("SELECT * FROM organizations");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);