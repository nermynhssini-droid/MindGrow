<?php


require_once "../config/bootstrap.php"; 

/* ================= SECURITY ================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/index.php?page=home");
    exit;
}

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

   $stmt = $conn->prepare("SELECT id, name, role FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User introuvable");
}

if ($user['role'] === 'admin') {
    echo "<script>
alert('❌ Impossible de supprimer un admin');
window.location.href = '../public/index.php?page=admin';
</script>";
exit;
}

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);


    $stmt = $conn->prepare("
        SELECT id, details
        FROM admin_logs
        WHERE action = 'deleted_user'
        LIMIT 1
    ");

    $stmt->execute();
    $existingLog = $stmt->fetch(PDO::FETCH_ASSOC);

    $newUser = [
        "id" => $user['id'],
        "name" => $user['name'],
        "deleted_at" => date("Y-m-d H:i:s")
    ];

    if ($existingLog) {

        $details = json_decode($existingLog['details'], true);

        if (!isset($details['deleted_users'])) {
            $details['deleted_users'] = [];
        }

        $details['deleted_users'][] = $newUser;

        $stmt = $conn->prepare("
            UPDATE admin_logs
            SET details = ?
            WHERE id = ?
        ");

        $stmt->execute([
            json_encode($details),
            $existingLog['id']
        ]);

    } else {

        $details = json_encode([
            "deleted_users" => [$newUser]
        ]);

        $stmt = $conn->prepare("
            INSERT INTO admin_logs
            (admin_id, action, is_deleted, details)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $_SESSION['user_id'],
            "deleted_user",
            1,
            $details
        ]);
    }

    header("Location: ../public/index.php?page=admin");
    exit;
}