<?php
    $name = "";
    $email = "";
    $password = "";
    $email_err = "";
    $password_err = "";
    
    if(isset($_POST['submit']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmPassword'])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        if($_POST['confirmPassword'] != $password){
            $password_err = "Password do not match";
        } else {
            include('./config/db_connect.php');
            $sql = "SELECT * FROM user WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($stmt, "s", $email);

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            if (mysqli_num_rows($result) > 0){
                $email_err = "Email already in use. Please try another.";
            } else {
                $sql = "INSERT INTO user (name, email, password) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed_password);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Registered Successfully')</script>";
                    echo "<script>window.location.href = 'login.php';</script>";
                    exit();
                  } else {
                    echo "<script>alert('Error:  Failed to register')</script>";
                  }
            }

        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./css/register.css">
</head>
<body>
    <form action="" method="post">
    <h1>Register</h1>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Name" required value="<?php echo $name ?>">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="email" required value="<?php echo $email ?>">
        <p class="error-msg"><?php echo $email_err ?></p>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="password" minlength="8" required value="<?php echo $password ?>">
        <label for="confirmPassword">Password</label>
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="confirm password" required>
        <p class="error-msg"><?php echo $password_err ?></p>
        <input type="submit" name="submit" id="">
        <p class="already-have-txt">Already have a Account? <a href="login.php">login</a></p>
    </form>
</body>
</html>