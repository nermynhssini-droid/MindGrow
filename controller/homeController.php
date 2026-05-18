<?php

require_once "../config/bootstrap.php";
require_once "../config/Database.php";
require_once "../model/DAO/OrganizationDAO.php";


/* =========================
   AUTO LOGIN VIA COOKIE
========================= */
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {

    $token = $_COOKIE['remember_token'];

    $db = new Database();
    $conn = $db->getConnection();

    $sql = "SELECT * FROM users WHERE remember_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$token]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user['name'];
        $_SESSION['role'] = $user['role'];
    }
}

/* =========================
   THEME SWITCH
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['theme'])) {

    $newTheme = $_POST['theme'];

    $cookiePrefs = json_decode(
        $_COOKIE['cookie_preferences'] ?? '{}',
        true
    );

    $themeAllowed = $cookiePrefs['theme'] ?? true;

    if ($themeAllowed) {

        setcookie(
            'theme',
            $newTheme,
            time() + (86400 * 30),
            '/'
        );
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/* =========================
   CURRENT THEME
========================= */
$theme = $_COOKIE['theme'] ?? 'light';

$db = new Database();
$conn = $db->getConnection();

class HomeController {

    public function index() {

        if (!isset($_SESSION['user_id'])) {
            header("Location:http://localhost/mindgrow/public/index.php?page=admin");
exit;
        }

        /* DATABASE */
        $db = new Database();
        $conn = $db->getConnection();

        $dao = new OrganizationDAO($conn);
        $orgs = $dao->getAll();

        /* COOKIE VISITES */
        

        if (isset($_POST['reset_visits'])) {

            $visitKey = "nb_visites";
            $lastKey = "last_visit";

            setcookie($visitKey, "", time() - 3600, "/");
            setcookie($lastKey, "", time() - 3600, "/");

            unset($_SESSION['visit_initialized']);

            header("Location: http://localhost/mindgrow/public/index.php?page=home");
exit;
        }

        $lastVisit = $_COOKIE['last_visit'] ?? null;

        $visitKey = "nb_visites";

        $current = isset($_COOKIE[$visitKey]) ? (int) $_COOKIE[$visitKey] : 0;

        if (!isset($_SESSION['visit_initialized'])) {

            $nbVisites = ($current == 0) ? 1 : $current;

            $_SESSION['visit_initialized'] = true;

        } else {

            $nbVisites = $current + 1;
        }

        setcookie($visitKey, $nbVisites, time() + (86400 * 30), "/");
        setcookie("last_visit", date("Y-m-d H:i:s"), time() + (86400 * 30), "/");
        $theme = $_COOKIE['theme'] ?? 'light';

        require "../view/home.php";
    }
}