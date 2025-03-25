<?php
$servername = "localhost";
$username = "root"; // Default in XAMPP
$password = ""; // Default in XAMPP
$database = "hospital_db";

$con = new mysqli($servername, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>