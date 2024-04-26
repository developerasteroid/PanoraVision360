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
    include('./../config/db_connect.php');
    $sql = "SELECT * FROM images WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "i", $_GET['id']);

    // Execute the prepared statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $description = $row['description'];
        $position = $row['position'];
        $image_name = $row['image'];
    } else {
        header("Location: index.php");
        exit();
    }
    function update(){
        include('../config/db_connect.php');
        global $position;
        global $image_name;
        if(isset($_POST['name']) && isset($_POST['description'])){
            $iname = $_POST['name'];
            $idescription = $_POST['description'];
            if(isset($_POST['position']) && $_POST['position'] != $position){
                if($_POST['position'] == "" || $_POST['position'] < 1){
                    $position = null;
                } else {
                    $position = $_POST['position'];
                }
            } 
            if($_FILES['image']['tmp_name'] != ""){
                $file_name = $_FILES['image']['name'];
                $file_temp = $_FILES['image']['tmp_name'];
                if($_FILES['image']['error']===0){

                    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                    $file_ext_lc = strtolower($file_ext);

                    $new_file_name = uniqid('IMG-', true).'.'.$file_ext_lc;
                    $file_upload_path = '../images/'.$new_file_name;

                    if(move_uploaded_file($file_temp, $file_upload_path)){
                        if(file_exists('../images/'.$image_name)){
                            unlink('../images/'.$image_name);
                        }
                        $image_name = $new_file_name;
                        
                    } else {
                        echo "<script>alert('Failed to update. Unkown error occurred!')</script>";
                        return;
                    }

                } else {
                    echo "<script>alert('Failed to update. Unkown error occurred in image update!')</script>";
                    return;
                }
            }
            $sql = "UPDATE images SET name = ?, description = ?, image = ?, position = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssii", $iname, $idescription, $image_name, $position, $_GET['id']);
    
            if (mysqli_stmt_execute($stmt)) {
                header("Location: manageImages.php");
                exit();
            } else {
                echo "<script>alert('Error:  Not able to update record')</script>";
            }
        }
    }

    if(isset($_POST['submit'])){
        update();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Update Image</title>
    <link rel="stylesheet" href="./css/all.css">
</head>
<body>
    <form
      action=""
      method="post"
      enctype="multipart/form-data"
      class="update-image-form"
    >
      <h1>Update Image</h1>
      <label for="name">Name</label>
      <input type="text" name="name" id="name" placeholder="Name" required value="<?php echo $name ?>"/>
      <label for="image">Change Image</label>
      <input type="file" name="image" accept="image/*" id="image" />
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
      ><?php echo $description ?></textarea>
      <label for="position">Position</label>
      <input type="number" name="position" id="position" placeholder="default" value="<?php echo $position ?>">
      <input type="submit" name="submit" id="submit" />
    </form>
</body>
</html>