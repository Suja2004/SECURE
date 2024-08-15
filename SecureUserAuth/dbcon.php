<?php
// Include database credentials securely
include_once 'config.php'; // Assuming config.php contains secure database connection details

// Create a new mysqli object and connect to the database
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check for connection errors
if ($con->connect_error) {
    // Log error securely
    error_log("Connection failed: " . $con->connect_error);
    die("Could not connect to MySQL");
}
