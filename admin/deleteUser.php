<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    include('../config/db_connect.php');
    $sql = 'DELETE FROM user WHERE id = ?';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        echo "Error while deleting User with id $id";
        exit;
    }
}


if (isset($_SERVER['HTTP_REFERER'])) {
    $referer_url = $_SERVER['HTTP_REFERER'];
    header("Location: $referer_url");
    exit;
} else {
    header("Location: manageUsers.php");
}
