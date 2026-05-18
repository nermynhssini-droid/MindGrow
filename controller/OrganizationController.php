<?php

require_once "../config/bootstrap.php";
require_once "../model/DAO/OrganizationDAO.php";




$conn = db();
$dao = new OrganizationDAO($conn);


if (isset($_POST['add'])) {

    $name = $_POST['name'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    $uploadPath = "../uploads/images/" . $image;

    move_uploaded_file($tmp, $uploadPath);

    $dao->add($name, $image);

    $org_id = $conn->lastInsertId();


    if (!empty($_POST['certifications'])) {

        foreach ($_POST['certifications'] as $cert) {

            if (!empty($cert)) {

                $stmt = $conn->prepare("
                    INSERT INTO certifications (name, org_id)
                    VALUES (?, ?)
                ");

                $stmt->execute([$cert, $org_id]);
            }
        }
    }

    header("Location: ../view/admin.php");
    exit;
}


if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $dao->delete($id);

    header("Location: ../view/admin.php");
    exit;
}


if (isset($_GET['edit'])) {

    session_start();
    $_SESSION['edit_org_id'] = $_GET['edit'];

    header("Location: ../view/admin.php");
    exit;
}


if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];

    /* GET OLD IMAGE VIA DAO */
    $old = $dao->getById($id);

    $image = $old['image'];

    /* NEW IMAGE */
    if (!empty($_FILES['image']['name'])) {

        unlink("../uploads/images/" . $old['image']);

        $image = time() . $_FILES['image']['name'];

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../uploads/images/" . $image
        );
    }

    $dao->update($id, $name, $image);

    header("Location: ../view/admin.php");
    exit;
}