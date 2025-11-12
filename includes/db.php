<?php

// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty
$dbname = "idea_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to UTF-8
// This is good practice for web apps
$conn->set_charset("utf8mb4");

?>