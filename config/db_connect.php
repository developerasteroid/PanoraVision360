<?php

// Create connection
$conn = mysqli_connect("localhost", "root", "", "panoravision360");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
