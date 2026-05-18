<?php




session_set_cookie_params(0);

session_start();

if (!isset($_SESSION['user_id']) && !empty($_COOKIE['remember_token'])) {

    require_once "../config/database.php";

    $db = new Database();
    $conn = $db->getConnection();

    $token = $_COOKIE['remember_token'];

    $sql = "SELECT * FROM users WHERE remember_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$token]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        // LOGIN AUTO
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user['name'];
        $_SESSION['role'] = $user['role'];

    } else {

        // TOKEN INVALIDE → CLEAN COMPLET
        setcookie("remember_token", "", time() - 3600, "/");
    }
}

/* =========================
   HELPERS (OPTIONNEL)
========================= */

// fonction utile pour vérifier login facilement
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// fonction pour protéger pages
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /mindgrow/public/index.php?page=login");
        exit;
    }
}
/* =========================
   COOKIE VISITES (FIX LOGIN ISSUE)
========================= */
date_default_timezone_set("Africa/Tunis");

if (isset($_POST['reset_visits'])) {

    setcookie("nb_visites", "", time() - 3600, "/");
    setcookie("last_visit", "", time() - 3600, "/");

    header("Location: index.php?page=home");
    exit;
}

/* 🔥 IMPORTANT : éviter double increment */
if (!isset($_COOKIE['visited_home'])) {

    $nbVisites = isset($_COOKIE['nb_visites'])
        ? (int) $_COOKIE['nb_visites'] + 1
        : 1;

    setcookie("nb_visites", $nbVisites, time() + 86400 * 30, "/");

    setcookie("visited_home", "1", time() + 3600, "/"); // bloque double comptage

} else {

    $nbVisites = (int) ($_COOKIE['nb_visites'] ?? 1);
}

$lastVisit = $_COOKIE['last_visit'] ?? null;

setcookie("last_visit", date("Y-m-d H:i:s"), time() + 86400 * 30, "/");
function addAdminLog($conn, $action, $targetId = null) {

    if (!isset($_SESSION['user_id'])) return;

    $sql = "INSERT INTO admin_logs (admin_id, action, target_id)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $_SESSION['user_id'],
        $action,
        $targetId
    ]);
}

if (isset($_POST['reset_theme'])) {
    setcookie("theme", "", time() - 3600, "/");
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=" . ($_GET['page'] ?? 'home'));
    exit;
}


if (isset($_POST['theme'])) {
    setcookie("theme", $_POST['theme'], time() + (30 * 24 * 60 * 60), "/");
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=" . ($_GET['page'] ?? 'home'));
    exit;
}


$theme = $_COOKIE['theme'] ?? 'light';




$page = $_GET['page'] ?? 'home';

function db() {
    require_once "../config/database.php";
    $db = new Database();
    return $db->getConnection();
}
