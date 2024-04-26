<?php
    session_start();

    if(isset($_SESSION['admin'])){
        header("Location: index.php");
        exit();
    }

    $username_err ="";
    $password_err = "";
    $username = "";
    $password = "";

    if(isset($_POST['username']) && isset($_POST['password'])){
        include('./../config/db_connect.php');

        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM admin WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);

        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "s", $username);

        // Execute the prepared statement
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);  
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin'] = true;
                $_SESSION['admin_name'] = $row['name'];
                header("Location: index.php");
                exit();
            } else {
                $password_err = "Incorrect password";
            }
        } else {
            $username_err = "User not found";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Login</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <form action="" method="post">
        <h2>Admin Login</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required value="<?php echo $username ?>">
        <p class="error_message"><?php echo $username_err ?></p>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required value="<?php echo $password ?>">
        <p class="error_message"><?php echo $password_err ?></p>
        <input type="submit" value="Login">
    </form>
</body>
</html>
