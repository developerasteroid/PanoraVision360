<?php
    session_start();
    if(!isset($_SESSION['admin'])){
         // Redirect to the login page
        header("Location: login.php");
        exit();
    }

    if(!isset($_GET['id'])){
        header("Location: index.php");
        exit();
    }
    include('../config/db_connect.php');

    $sql = 'SELECT * FROM images WHERE id = ?';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $file_name = $row['image'];
    } else {
        echo "No record Found";
        echo '<br><a href="index.php">Go to Dashboard</a>';
        exit();
    }

    mysqli_stmt_close($stmt);


    $sql = 'DELETE FROM images WHERE id = ?';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
    if(mysqli_stmt_execute($stmt)){
        if(mysqli_stmt_affected_rows($stmt) > 0){
            if(file_exists('../images/'.$file_name)){
                unlink('../images/'.$file_name);
            }
            header("Location: manageImages.php");
            exit();
        } else {
            echo "No record Found with id: " . $_GET['id'];
        }
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }

?>