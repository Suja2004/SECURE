<?php
session_start();

// Logout any existing session
if (isset($_SESSION["email"])) {
    session_destroy();
    session_start();
}

include_once 'dbcon.php';
include_once 'index.php';

// Get input values
$email = $_POST['email'];
$password = $_POST['password'];

// Validate inputs
if (empty($email) || empty($password)) {
    header("location: index.php?w=Invalid input");
    exit;
}

// Sanitize the email input
$email = $con->real_escape_string($email);

// Construct the SQL query using prepared statement
$stmt = $con->prepare("SELECT username, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $hashedPassword = $row['password'];
    
    if (password_verify($password, $hashedPassword)) {
        
        session_regenerate_id(true);
        $_SESSION["username"] = $username;
        $_SESSION["email"] = $email; 
        header("location: profile.php");
        exit;
    } 
} 
    echo "<script>
        showPopup('Wrong Email or Password');
        </script>";

?>
