<?php

require_once "../config/bootstrap.php";
require_once "../model/DAO/UserDAO.php";

$conn = db();
$dao = new UserDAO($conn);


/* ================= REMOVE TOKEN ================= */
if (isset($_SESSION['user_id'])) {

    $dao->removeRememberToken($_SESSION['user_id']);
}


/* ================= SESSION DESTROY ================= */
session_unset();
session_destroy();


/* ================= DELETE COOKIES ================= */
setcookie("remember_token", "", time() - 3600, "/");
setcookie("visited_home", "", time() - 3600, "/");
setcookie("nb_visites", "", time() - 3600, "/");
setcookie("last_visit", "", time() - 3600, "/");


/* ================= REDIRECT ================= */
header("Location: index.php?page=login");
exit;