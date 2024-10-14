<?php
$servername = "localhost:3305";
$username = "root";
$password = "";
$database = "student_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
