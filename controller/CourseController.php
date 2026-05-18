<?php


require_once "../config/bootstrap.php";
require_once "../model/DAO/CourseDAO.php";





$conn = db();

$dao = new CourseDAO($conn);


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/index.php?page=login");
    exit;
}



if (isset($_POST['add'])) {

    $title = $_POST['title'];
    $content = $_POST['content'] ?? null;
    $cert_id = $_POST['cert_id'];

    $filePath = null;

    if (!empty($_FILES['file']['name'])) {

        $fileName = time() . "_" . $_FILES['file']['name'];
        $fileTmp = $_FILES['file']['tmp_name'];

        $allowed = ['txt', 'pdf', 'doc', 'docx'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            die("Format non autorisé");
        }

        $folder = "../uploads/courses/";

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $filePath = "uploads/courses/" . $fileName;

        move_uploaded_file($fileTmp, "../" . $filePath);
    }

    $dao->add($title, $content, $cert_id, $filePath);

    header("Location: ../public/index.php?page=admin");
    exit;
}


if (isset($_GET['edit'])) {

    $_SESSION['edit_course_id'] = $_GET['edit'];

    header("Location: ../public/index.php?page=admin");
    exit;
}



if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'] ?? null;
    $cert_id = $_POST['cert_id'];

    $filePath = null;

    if (!empty($_FILES['file']['name'])) {

        $stmt = $conn->prepare("SELECT file_path FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        $oldCourse = $dao->getById($id);

        if ($oldCourse && !empty($oldCourse['file_path'])) {
            $oldPath = "../" . $oldCourse['file_path'];
            if (file_exists($oldPath)) unlink($oldPath);
        }

        $fileName = time() . "_" . $_FILES['file']['name'];
        $fileTmp = $_FILES['file']['tmp_name'];

        $filePath = "uploads/courses/" . $fileName;

        move_uploaded_file($fileTmp, "../" . $filePath);
    }

    $dao->update($id, $title, $content, $cert_id, $filePath);

    header("Location: ../public/index.php?page=admin");
    exit;
}


if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $conn->prepare("SELECT file_path FROM courses WHERE id = ?");
    $stmt->execute([$id]);
    $course = $dao->getById($id);

    if ($course && !empty($course['file_path'])) {
        $fullPath = "../" . $course['file_path'];
        if (file_exists($fullPath)) unlink($fullPath);
    }

    $dao->delete($id);

    header("Location: ../public/index.php?page=admin");
    exit;
}


if (isset($_GET['cancel'])) {

    unset($_SESSION['edit_course']);

    header("Location: ../public/index.php?page=admin");
    exit;
}



header("Location: ../public/index.php?page=admin");
exit;