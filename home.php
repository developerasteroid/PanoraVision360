<?php
session_start();
if (!isset($_SESSION['user'])) {
    // Redirect to the login page
    header("Location: index.php");
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
    <style>
        .placeOptionBtn {
            background: #000;
            color: #fff;
        }

        /* Style the select element */
        .custom-select {
            position: relative;
            display: inline-block;
        }

        .custom-select select {
            width: 200px;
            /* Adjust width as needed */
            /* height: 40px; */
            /* Adjust height as needed */
            border: none;
            border-radius: 5px;
            background-color: #555;
            color: #fff;
            font-size: 18px;
            padding: 5px 10px;
            /* -webkit-appearance: none; */
            /* For older versions of WebKit browsers */
            /* -moz-appearance: none; */
            /* For older versions of Firefox browsers */
            appearance: none;
            cursor: pointer;

            /* Removes default appearance */
        }

        /* Style the arrow icon */
        .custom-select::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 15px;
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            /* Adjust size of the arrow */
            border-right: 6px solid transparent;
            /* Adjust size of the arrow */
            border-top: 6px solid #fff;
            /* Adjust color of the arrow */
            transform: translateY(-50%);
            cursor: pointer;
            pointer-events: none;
        }

        /* Style select when it's focused */
        .custom-select select:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
            /* Example focus shadow */
        }

        /* Style the options */
        .custom-select select option {
            background-color: #444;
            color: #fff;
            padding: 10px;
        }

        /* Style the options on hover */
        .custom-select select option:hover {
            background-color: #666;
        }

        @media screen and (max-width: 600px) {
            .custom-select select {
                width: 130px;
                font-size: 15px;
            }

            .custom-select select option {
                font-size: 15px;
                padding: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="header-container" id="nav-header-container">
        <h1>PanoraVision</h1>
        <div class="user-action-container">
            <div class="custom-select">
                <select id="selectImage" onchange="panoramaView('./images/' + this.value)">
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
                        $firstImage = "";
                        while ($row = mysqli_fetch_assoc($result)) {
                            $image = $row['image'];
                            $name = $row['name'];
                            echo "<option value='$image'>$name</option>";
                            if (empty($firstImage)) {
                                $firstImage = $image;
                            }
                        }
                    }
                    ?>
                </select>
            </div>

            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div id="panorama"></div>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
    <script src="./js/Panorama.js"></script>
    <script>
        <?php
        if (isset($firstImage) && !empty($firstImage)) {
            echo 'panoramaView("./images/' . $firstImage . '");';
        }
        ?>
    </script>
</body>

</html>