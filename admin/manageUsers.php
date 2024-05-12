<?php
session_start();
if (!isset($_SESSION['admin'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

include('../config/db_connect.php');
$slnoStart = 0;
$results_per_page = 10;

// Determine current page number
if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}

// Calculate SQL OFFSET
$offset = ($page - 1) * $results_per_page;
$slnoStart = $offset;

// Initialize search variables
$search_term = "";


if (isset($_GET['search'])) {
    $search_term = $_GET['search'];
}
$sql = "SELECT * FROM user WHERE 1=1";

if (!empty($search_term)) {
    $sql .= " AND (name LIKE '%$search_term%' OR email LIKE '%$search_term%')";
}

$sql .= " LIMIT $offset, $results_per_page";


$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Pagination links
$sql = "SELECT COUNT(*) AS total FROM user WHERE 1=1";

// Add search condition for name or email
if (!empty($search_term)) {
    $sql .= " AND (name LIKE '%$search_term%' OR email LIKE '%$search_term%')";
}
$pageResult = $conn->query($sql);
$totalRow = $pageResult->fetch_assoc();
$total_pages = ceil($totalRow["total"] / $results_per_page)

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Manage User</title>
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
        <div class="manage-users-container">
            <div class="mu-user-navigation-container">

                <div class="mu-page-no-container">

                    <?php
                    $pages_to_show = 5; // Adjust this number as needed

                    $start_page = max(1, $page - floor($pages_to_show / 2));
                    $end_page = min($total_pages, $start_page + $pages_to_show - 1);

                    if (empty($search_term)) {
                        if ($page > 1) {
                            echo "<a href='?page=" . ($page - 1) . "'>&lt;</a> ";
                        }
                        for ($i = $start_page; $i <= $end_page; $i++) {
                            if ($page == $i) {
                                echo "<a class='activePage' href='?page=$i'>$i</a> ";
                            } else {
                                echo "<a href='?page=$i'>$i</a> ";
                            }
                        }
                        if ($page < $total_pages) {
                            echo "<a href='?page=" . ($page + 1) . "'>&gt;</a> ";
                        }
                    } else {
                        if ($page > 1) {
                            echo "<a href='?search=$search_term&page=" . ($page - 1) . "'>&lt;</a> ";
                        }
                        for ($i = $start_page; $i <= $end_page; $i++) {
                            if ($page == $i) {
                                echo "<a class='activePage' href='?search=$search_term&page=$i'>$i</a> ";
                            } else {
                                echo "<a href='?search=$search_term&page=$i'>$i</a> ";
                            }
                        }
                        if ($page < $total_pages) {
                            echo "<a href='?search=$search_term&page=" . ($page + 1) . "'>&gt;</a> ";
                        }
                    }
                    ?>
                </div>
                <div class="mu-search-user-container">
                    <form action="" method="get">
                        <input type="text" name="search" placeholder="Search" value="<?php echo $search_term ?>">
                        <button type="submit">Search</button>
                    </form>
                </div>

            </div>
            <div class="mu-user-list-table-container">
                <table>
                    <tr>
                        <th>Sl.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>


                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . ($slnoStart + $i) . "</td>";
                            echo "<td>{$row['name']}</td>";
                            echo "<td>{$row['email']}</td>";
                            echo "<td><a class='deleteUserBtn' href='deleteUser.php?id={$row['id']}' onclick='return confirmDeleteUser(\"{$row['email']}\")'>Delete</a></td>";
                            echo "</tr>";
                            $i++;
                        }
                    } else {
                        echo "<tr>";
                        echo "<td style='color: red;' colspan='4'>No User Record Found</td>";
                        echo "</tr>";
                    }
                    ?>




                </table>

            </div>
        </div>

    </div>


    <script>
        function confirmDeleteUser(email) {
            let confirm = window.confirm(`Are you sure you want to delete user ${email}?`);
            if (confirm) {
                return true;
            } else {
                return false;
            }
        }
    </script>
</body>

</html>