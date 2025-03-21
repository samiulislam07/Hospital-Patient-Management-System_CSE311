<?php
$servername = "localhost";
$username = "root"; // Default in XAMPP
$password = ""; // Default in XAMPP
$database = "hospital_db";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
