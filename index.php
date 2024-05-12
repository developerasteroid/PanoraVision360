<?php
session_start();
if (isset($_SESSION['user'])) {
  // Redirect to the login page
  header("Location: home.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PANORAVISION360</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css" />
  <link rel="stylesheet" href="./css/index.css" />
</head>

<body>
  <div class="header-container" id="nav-header-container">
    <h1>PanoraVision</h1>
    <div class="user-action-container">
      <a href="login.php">Login</a> |
      <a href="register.php">Sign up</a>
    </div>
  </div>

  <div id="panorama"></div>

  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
  <script src="./js/Panorama.js"></script>
  <script>
    <?php
    include('./config/db_connect.php');
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
      $row = mysqli_fetch_assoc($result);
      $image = $row['image'];
      echo 'panoramaView("./images/' . $image . '");';
    }
    ?>
  </script>
</body>

</html>