<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}
header('location: manageImages.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Dashboard</title>
    <link rel="stylesheet" href="./css/all.css">
</head>

<body>

    <?php
    include('./includes/header.php');
    ?>

    <div class="main-container">
        <?php
        include('./includes/sideNav.php');
        ?>
        <div class="index-component-container">

        </div>

    </div>


</body>

</html>