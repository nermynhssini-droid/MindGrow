<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>MindGrow</title>

<link rel="stylesheet" href="/mindgrow/assets/css/style.css?v=<?= time() ?>">

<?php

require_once "../controller/homeController.php";
require_once "../config/bootstrap.php";


?>


<style>
.hidden {
    display: none;
}
</style>

</head>

<body class="dashboard <?= $theme ?>">

<!-- NAVBAR -->
<div class="navbar">

    <h2>MindGrow</h2>

    <div class="nav-actions">

        <form method="POST">

            <input
                type="hidden"
                name="theme"
                value="<?= $theme === 'light' ? 'dark' : 'light' ?>"
            >

            <button class="theme-btn" type="submit">

                <?= $theme === 'light' ? '🌙' : '☀️' ?>

            </button>

        </form>
        <form method="POST">

    <button class="theme-btn" type="submit" name="reset_theme">
        🔄
    </button>

</form>

        <a href="../public/logout.php" class="logout">
            Logout
        </a>

    </div>

</div>
<div class="visit-box">

    <?php
        $nbVisitesSafe = $nbVisites ?? 0;
        $lastVisitSafe = $lastVisit ?? null;
    ?>

    <?php if ($nbVisitesSafe <= 1): ?>

        <p>C'est votre première visite 👋</p>

    <?php else: ?>

        <p>
            Vous avez visité cette page
            <?= $nbVisitesSafe ?> fois
        </p>

    <?php endif; ?>

    <?php if (!empty($lastVisitSafe)): ?>

        <p>
            Dernière visite :
            <?= htmlspecialchars($lastVisitSafe) ?>
        </p>

    <?php endif; ?>

    <form method="POST" action="http://localhost/mindgrow/public/index.php?page=home">

        <button type="submit" name="reset_visits">
            Réinitialiser compteur
        </button>

    </form>

</div>



<!-- ================= LEVEL 1 ================= -->
<div id="orgPage">

    <div class="welcome">
        <h1>Choisis une certification 🎓</h1>
        <p>Organismes disponibles</p>
    </div>

    <div class="grid" id="orgGrid"></div>

</div>

<!-- ================= LEVEL 2 ================= -->
<div id="certPage" class="hidden">

    <div class="navbar">
        <h2>Certifications</h2>

        <button class="logout" onclick="backToOrg()">
            Retour
        </button>
    </div>

    <div class="welcome">
        <h1 id="certTitle"></h1>
    </div>

    <div class="grid" id="certGrid"></div>

</div>

<!-- ================= LEVEL 3 ================= -->
<div id="coursePage" class="hidden">

    <div class="navbar">
        <h2>Cours</h2>

        <button class="logout" onclick="backToCert()">
            Retour
        </button>
    </div>

    <div class="welcome">
        <h1 id="courseTitle"></h1>
    </div>

    <div class="grid" id="courseContent"></div>

</div>



<script src="/mindgrow/assets/js/script.js?v=<?= time() ?>"></script>

</body>
</html>