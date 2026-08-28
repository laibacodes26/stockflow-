<?php
$conn = mysqli_connect("localhost", "root", "", "ecommerce_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
