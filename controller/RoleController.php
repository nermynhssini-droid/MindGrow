<?php

require_once "../config/bootstrap.php";


$conn = db();
if (isset($_GET['id']) && isset($_GET['role'])) {

    $id = $_GET['id'];
    $role = $_GET['role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$role, $id]);

    header("Location: ../view/admin.php#users");
    exit;
}
addAdminLog($conn, "Changed role to $role", $id);