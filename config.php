<?php
session_start();

// YOUR EXACT DATABASE NAME (with space and underscore)
$conn = mysqli_connect("localhost", "root", "", "student_and acedamic management system");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
?>