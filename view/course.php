<?php

require_once "../controller/CourseController.php";


$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID cours manquant");
}

/* =========================
   GET COURSE DATA
========================= */
$stmt = $conn->prepare("
    SELECT c.*, cert.name AS cert_name
    FROM courses c
    LEFT JOIN certifications cert ON c.cert_id = cert.id
    WHERE c.id = ?
");

$stmt->execute([$id]);
$course = $stmt->fetch();

if (!$course) {
    die("Cours introuvable");
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $course['title'] ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="dashboard">

<div class="navbar">
    <h2><?= $course['title'] ?></h2>
    <a href="../public/index.php?page=home" class="logout">⬅ Retour</a>
</div>

<div class="card" style="max-width:800px;margin:50px auto;">

    <h2><?= $course['title'] ?></h2>

    <p>🎓 Certification : <?= $course['cert_name'] ?></p>

    <p><?= $course['content'] ?></p>

    <hr>

    <?php if (!empty($course['file_path'])): ?>
        <a class="btn primary"
           href="../<?= $course['file_path'] ?>"
           target="_blank">
            📄 Ouvrir le fichier
        </a>
    <?php else: ?>
        <p>Aucun fichier disponible</p>
    <?php endif; ?>

</div>

</body>
</html>