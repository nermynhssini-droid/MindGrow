<?php

require_once "../config/bootstrap.php";



$conn = db();


$usersCount = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$coursesCount = $conn->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$certsCount = $conn->query("SELECT COUNT(*) FROM certifications")->fetchColumn();
$orgsCount = $conn->query("SELECT COUNT(*) FROM organizations")->fetchColumn();


$users = $conn->query("SELECT * FROM users")->fetchAll();

$courses = $conn->query("
    SELECT courses.*, certifications.name AS cert_name
    FROM courses
    LEFT JOIN certifications ON courses.cert_id = certifications.id
")->fetchAll();

$orgs = $conn->query("SELECT * FROM organizations")->fetchAll(); 


$editCourse = null;

if (isset($_SESSION['edit_course_id'])) {

    $stmt = $conn->prepare("SELECT * FROM courses WHERE id=?");
    $stmt->execute([$_SESSION['edit_course_id']]);

    $editCourse = $stmt->fetch();

    unset($_SESSION['edit_course_id']);
}

$editCert = null;

if (isset($_SESSION['edit_cert_id'])) {

    $stmt = $conn->prepare("SELECT * FROM certifications WHERE id=?");
    $stmt->execute([$_SESSION['edit_cert_id']]);

    $editCert = $stmt->fetch();

    unset($_SESSION['edit_cert_id']);
}
$editOrg = null;

if (isset($_SESSION['edit_org_id'])) {

    $stmt = $conn->prepare("SELECT * FROM organizations WHERE id=?");
    $stmt->execute([$_SESSION['edit_org_id']]);

    $editOrg = $stmt->fetch();

    unset($_SESSION['edit_org_id']);
}