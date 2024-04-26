<?php
    session_start();
    if(isset($_SESSION['user'])){
         // Redirect to the login page
        header("Location: home.php");
        exit();
    }
    $email_err ="";
    $password_err = "";
    $email = "";
    $password = "";
    if(isset($_POST['submit']) && isset($_POST['email']) && isset($_POST['password'])){
        include('./config/db_connect.php');
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $email);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);  
            if (password_verify($password, $row['password'])) {
                $_SESSION['user'] = true;
                $_SESSION['user_name'] = $row['name'];
                header("Location: home.php");
                exit();
            } else {
                $password_err = "Incorrect password";
            }
        } else {
            $email_err = "User with this email not found";
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <form action="" method="post">
        <h1>Login</h1>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="email" required value="<?php echo $email ?>">
        <p class="error-msg"><?php echo $email_err ?></p>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="password" required value="<?php echo $password ?>">
        <p class="error-msg"><?php echo $password_err ?></p>
        <input type="submit" name="submit" id="">
        <p class="create-new-txt">Dont have a Account? <a href="register.php">create new</a></p>
    </form>
</body>
</html>