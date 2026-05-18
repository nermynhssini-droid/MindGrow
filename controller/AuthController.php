<?php

require_once "../config/bootstrap.php";
require_once "../model/DAO/UserDAO.php";


$conn = db();

$dao = new UserDAO(db());


if(isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if($dao->create($name, $email, $password)) {
        header("Location: ../public/index.php?page=login");
        exit;
    } else {
        echo "Register failed";
    }
}

if(isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $dao->getByEmail($email);

    if($user && password_verify($password, $user['password'])) {
        

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        

        if(!empty($_POST['remember'])) {

            $token = bin2hex(random_bytes(32));

            $dao->updateToken($user['id'], $token);

            setcookie("remember_token", $token, [
                'expires' => time() + (30 * 24 * 60 * 60),
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

        } else {

            $dao->clearToken($user['id']);

            setcookie("remember_token", "", time() - 3600, "/");
        }

        if($user['role'] === 'admin') {
            header("Location: ../public/index.php?page=admin");
        } else {
            header("Location: ../public/index.php?page=home");
        }

        exit;

    } else {
        echo "Invalid login";
    }
}


if(isset($_GET['logout'])) {

    if(isset($_SESSION['user_id'])) {
        $dao->clearToken($_SESSION['user_id']);
    }

    session_destroy();

    setcookie("remember_token", "", time() - 3600, "/");

    header("Location: ../public/index.php?page=login");
    exit;
}