<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin | Manage Image</title>
    <link rel="stylesheet" href="./css/all.css" />
</head>

<body>
    <?php
    include('./includes/header.php');
    ?>
    <div class="main-container">
        <?php
        include('./includes/sideNav.php');
        ?>

        <div class="images-list-container">
            <?php
            include('../config/db_connect.php');
            $sql = "SELECT * FROM images 
            ORDER BY 
                CASE
                    WHEN position IS NULL THEN 1
                    ELSE 0
                END ASC,
                position ASC, 
                CASE
                    WHEN position IS NULL THEN 0
                    ELSE updatedAt
                END DESC
            ";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="images-list-card">';
                    echo '<img src="./../images/' . $row['image'] . '" alt="...">';
                    echo '<div class="information-container">';
                    echo '<h3>' . $row['name'] . '</h3>';
                    echo '<p>' . $row['description'] . '</p>';
                    if ($row['position'] != null) {
                        echo '<p>Position: ' . $row['position'] . '</p>';
                    } else {
                        echo '<p>Position: Default</p>';
                    }
                    echo '</div>';
                    echo '<div class="action-container">';
                    echo '<a class="action-update" href="updateImage.php?id=' . $row['id'] . '">Update</a>';
                    echo '<a class="action-delete" href="deleteImage.php?id=' . $row['id'] . '" onclick="return confirmDelete()">Delete</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<div class='emptyListMsgDiv'><p>No Records Found</p>";
                echo '<a href="addNewImage.php">Add New</a></div>';
            }
            ?>


        </div>
    </div>
    <script>
        function confirmDelete() {
            let confirm = window.confirm('Are you sure you want to delete the image?');
            if (confirm) {
                return true;
            } else {
                return false;
            }
        }
    </script>
</body>

</html>