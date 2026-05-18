<?php

require_once "../config/bootstrap.php";
require_once "../model/DAO/CertificationDAO.php";


$conn = db();

$dao = new CertificationDAO($conn);

/* ================= ADD ================= */
if (isset($_POST['add'])) {

    if (!empty($_POST['name']) && !empty($_POST['org_id'])) {

        $dao->add(
            $_POST['name'],
            $_POST['org_id']
        );
    }

    header("Location: ../view/admin.php");
    exit;
}

/* ================= UPDATE ================= */
if (isset($_POST['update'])) {

    if (!empty($_POST['id']) && !empty($_POST['name']) && !empty($_POST['org_id'])) {

        $dao->update(
            $_POST['id'],
            $_POST['name'],
            $_POST['org_id']
        );
    }

    header("Location: ../view/admin.php");
    exit;
}

/* ================= DELETE ================= */
if (isset($_POST['delete'])) {

    if (!empty($_POST['id'])) {

        $dao->delete($_POST['id']);
    }

    header("Location: ../view/admin.php");
    exit;
}