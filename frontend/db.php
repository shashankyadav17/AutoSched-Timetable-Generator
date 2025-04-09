<?php
$host = "localhost";
$dbname = "autosched";  // ✅ use your actual database name
$username = "root";
$password = ""; // default for XAMPP

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
