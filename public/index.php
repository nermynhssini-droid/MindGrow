<?php
require_once "../config/bootstrap.php";

$conn = db();


switch($page) {

    case 'login':
        require "../view/login.php";
        break;

    case 'register':
        require "../view/register.php";
        break;

    case 'home':
        require_once "../controller/HomeController.php";
        $controller = new HomeController();
        $controller->index();
        break;

    case 'logout':
        require_once "../controller/AuthController.php";
        $auth = new AuthController();
        $auth->logout();
        break;

    case 'admin':

        if(!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=home");
            exit;
        }

        require "../view/admin.php";
        break;

    default:
        require "../view/landing.php";
        break;
}