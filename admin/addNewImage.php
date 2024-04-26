<?php
    session_start();
    if(!isset($_SESSION['admin'])){
         // Redirect to the login page
        header("Location: login.php");
        exit();
    }
    if(isset($_POST['submit'])){
      include('../config/db_connect.php');
      $name = $_POST['name'];
      $description = $_POST['description'];

      $file_name = $_FILES['image']['name']; 
      $file_temp = $_FILES['image']['tmp_name'];    
      $file_error = $_FILES['image']['error'];
      $folder = '../images/';

      if ($file_error === 0) {
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_ext_lc = strtolower($file_ext);

        $new_file_name = uniqid('IMG-', true).'.'.$file_ext_lc;
        $file_upload_path = $folder.$new_file_name;
        if(move_uploaded_file($file_temp, $file_upload_path)){
          $sql = "INSERT INTO images (name, description, image) VALUES (?, ?, ?)";
          $stmt = mysqli_prepare($conn, $sql);
          mysqli_stmt_bind_param($stmt, "sss", $name, $description, $new_file_name);

          if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Added New Image Successfully')</script>";
          } else {
            echo "<script>alert('Error:  Not able to add New record')</script>";
          }
        } else {
            echo "<script>alert('Failed to add. Unkown error occurred!')</script>";
        }
        
    } else {
        echo "<script>alert('Failed to add. Unkown error occurred!')</script>";
    }

    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin | Add new Image</title>
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
    <div class="add-new-image-form-container">

    <form
      action=""
      method="post"
      enctype="multipart/form-data"
      class="add-new-image-form"
    >
      <h1>Add New</h1>
      <label for="name">Name</label>
      <input type="text" name="name" id="name" placeholder="Name" required />
      <label for="image">Image</label>
      <input type="file" name="image" accept="image/*" id="image" required />
      <label for="description">Description</label>
      <textarea
        type="text"
        name="description"
        id="description"
        placeholder="Description"
        cols="50"
        rows="5"
        maxlength="1000"
        required
      ></textarea>
      <input type="submit" name="submit" id="submit" />
    </form>
    </div>

    </div>
  </body>
</html>
