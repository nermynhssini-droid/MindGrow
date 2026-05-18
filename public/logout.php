<?php

require_once "../config/bootstrap.php";
require_once "../model/DAO/UserDAO.php";

$conn = db();
$dao = new UserDAO($conn);


if (isset($_SESSION['user_id'])) {

    $dao->removeRememberToken($_SESSION['user_id']);
}


session_unset();
session_destroy();


setcookie("remember_token", "", time() - 3600, "/");
setcookie("visited_home", "", time() - 3600, "/");
setcookie("nb_visites", "", time() - 3600, "/");
setcookie("last_visit", "", time() - 3600, "/");


header("Location: index.php?page=login");
exit;